<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Destination;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // 1. Base Query - Fetch all approved packages
        $query = Package::with(['agency', 'destination', 'reviews'])
                        ->approved();

        // 2. Fetch metadata for dynamic search UI from all approved packages
        $dbFromCities = Package::approved()->whereNotNull('from_city')->pluck('from_city')->unique()->toArray();
        $dbDepartureCitiesArray = Package::approved()->get()->flatMap(function($p) {
            // Handle both Model and stdClass
            $cities = $p->departure_cities ?? [];
            if (is_string($cities)) {
                $cities = json_decode($cities, true) ?? [];
            }
            return is_array($cities) ? $cities : [];
        })->unique()->toArray();
        $uniqueDepartureCities = collect(array_merge($dbFromCities, $dbDepartureCitiesArray))->unique()->values()->all();
        sort($uniqueDepartureCities);

        $validDestinations = Destination::whereHas('packages', fn($q) => $q->approved())->pluck('name')->unique()->values()->all();

        // 4. Apply User Filters
        $isSearching = $request->exists('search') || $request->hasAny(['from', 'to', 'month', 'date', 'budget', 'duration']);
        
        $fromFilter = $request->input('from');
        $toFilter = $request->input('to');
        $monthFilter = $request->input('month');
        $dateFilter = $request->input('date');
        $budgetFilter = $request->input('budget');
        $durationFilter = $request->input('duration');
        $customDurationFilter = $request->input('custom_duration');

        // From Filter (Departure City)
        if ($fromFilter) {
            $query->where(function($q) use ($fromFilter) {
                $q->where('from_city', $fromFilter)
                  ->orWhereJsonContains('departure_cities', $fromFilter);
            });
        }

        // To Filter (Destination)
        if ($toFilter) {
            $query->whereHas('destination', function ($q) use ($toFilter) {
                $q->where('name', 'like', "%{$toFilter}%")
                  ->orWhere('location', 'like', "%{$toFilter}%");
            });
        }

        // Month Filter
        if ($monthFilter && !$dateFilter) {
            $monthNum = \Carbon\Carbon::parse($monthFilter)->month;
            $query->whereMonth('travel_date', $monthNum);
        }

        // Date Filter
        if ($dateFilter) {
            $query->whereDate('travel_date', $dateFilter);
        }

        // Budget Filter - Matching UI labels: Under 5000, 5000-10000, 10000-15000, 15000+
        if ($budgetFilter) {
            switch ($budgetFilter) {
                case '0-5000':
                case '0-1000': // Legacy support
                    $query->where('price', '<=', 5000);
                    break;
                case '5000-10000':
                case '1000-2000': // Legacy support
                    $query->whereBetween('price', [5001, 10000]);
                    break;
                case '10000-15000':
                case '2000-5000': // Legacy support
                    $query->whereBetween('price', [10001, 15000]);
                    break;
                case '15000+':
                    $query->where('price', '>', 15000);
                    break;
            }
        }

        // Duration Filter
        if ($durationFilter) {
            // We'll interpret ranges as integers
            // e.g. "1-3" -> 1 to 3 days
            // We assume 'duration' column stores something we can parse or number
            // If duration stores "3 days", we might need regex if DB isn't clean number.
            // But let's assume it's clean or castable for now, or use WhereBetween on numeric interpretation if possible
            // Given previous schema, duration is string. We might need to filter in PHP if it's complex string like "5 Days / 4 Nights".
            // Let's rely on PHP filtering for duration if strict SQL logic is hard for string formats.
        }

        $packages = $query->get();

        // 5. Post-Processing / PHP Filtering (for Duration string matching and Custom logic)
        $filteredPackages = $packages->filter(function ($package) use ($durationFilter, $customDurationFilter) {
            $matchesDuration = true;
            
            if ($durationFilter) {
                // Parse duration string "X days" or just "X"
                $days = 0;
                
                // Try to find "D" or "Days" explicitly first (e.g. "6N/7D", "5 Days")
                if (preg_match('/(\d+)\s*(?:D|d|Day|day)/', $package->duration, $m)) {
                    $days = (int)$m[1];
                } 
                // Fallback: just take the first number found (e.g. "5", "5 Nights")
                elseif (preg_match('/(\d+)/', $package->duration, $m)) {
                    $days = (int)$m[1];
                }
                
                // If simple cast works better (e.g. "5")
                if ($days === 0) {
                    $days = (int) $package->duration;
                }

                if ($durationFilter === 'custom' && $customDurationFilter) {
                    $matchesDuration = ($days === (int)$customDurationFilter);
                } else {
                    switch ($durationFilter) {
                        case '1-3': $matchesDuration = ($days >= 1 && $days <= 3); break;
                        case '4-7': $matchesDuration = ($days >= 4 && $days <= 7); break;
                        case '8-14': $matchesDuration = ($days >= 8 && $days <= 14); break;
                        case '15+': $matchesDuration = ($days >= 15); break;
                    }
                }
            }

            return $matchesDuration;
        });

        return view('frontend.search', [
            'filteredPackages' => $filteredPackages,
            'uniqueDepartureCities' => $uniqueDepartureCities,
            'allDestinations' => $validDestinations,
            'isSearching' => $isSearching,
            'fromFilter' => $fromFilter,
            'toFilter' => $toFilter,
            'monthFilter' => $monthFilter,
            'dateFilter' => $dateFilter,
            'budgetFilter' => $budgetFilter,
            'durationFilter' => $durationFilter,
            'customDurationFilter' => $customDurationFilter,
        ]);
    }
}
