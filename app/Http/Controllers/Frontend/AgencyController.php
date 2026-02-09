<?php
// app/Http/Controllers/AgencyController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Destination;
use App\Models\Agency;
use App\Helpers\AuthHelper;

class AgencyController extends Controller
{
    public function index(Request $request, $agency = null, $package = null)
    {
        // Get package ID and name from URL
        $packageId = $request->input('id');
        $packageName = $request->input('package', $package);

        // Get search filters from URL
        $fromFilter = $request->input('from', '');
        $toFilter = $request->input('to', '');
        $dateFilter = $request->input('date', '');
        $budgetFilter = $request->input('budget', '');
        $durationFilter = $request->input('duration', '');
        $monthFilter = $request->input('month', '');
        $priceFilter = $request->input('filter', 'default');

        // Agency contact numbers mapping
        $agencyContacts = $this->getAgencyContacts();

        // Find the specific package
        $selectedPackage = null;
        if ($packageId) {
            $selectedPackage = Package::with(['destination', 'agency'])->find($packageId);
        } elseif ($packageName) {
            $selectedPackage = Package::with(['destination', 'agency'])
                ->where('name', $packageName)
                ->first();
        }

        // Base query for packages (each represents an agency offering for that destination)
        $query = Package::with(['destination', 'agency'])->approved();

        if ($selectedPackage) {
            // If a specific package is selected, we primarily show alternatives/agencies for that destination
            $query->where('destination_id', $selectedPackage->destination_id);
        }

        // Apply search filters
        if (!empty($budgetFilter)) {
            switch ($budgetFilter) {
                case '0-1000': // Corresponds to < 5000 in user logic
                    $query->where('price', '<=', 5000);
                    break;
                case '1000-2000': // Corresponds to 5000-10000
                    $query->where('price', '>', 5000)->where('price', '<=', 10000);
                    break;
                case '2000-5000': // Corresponds to 10000-15000
                    $query->where('price', '>', 10000)->where('price', '<=', 15000);
                    break;
            }
        }

        if (!empty($durationFilter) && $durationFilter !== 'custom') {
            switch ($durationFilter) {
                case '1-3':
                    $query->whereRaw("CAST(duration AS INTEGER) BETWEEN 1 AND 3");
                    break;
                case '4-7':
                    $query->whereRaw("CAST(duration AS INTEGER) BETWEEN 4 AND 7");
                    break;
                case '8-14':
                    $query->whereRaw("CAST(duration AS INTEGER) BETWEEN 8 AND 14");
                    break;
                case '15+':
                    $query->whereRaw("CAST(duration AS INTEGER) >= 15");
                    break;
            }
        }

        if (!empty($fromFilter)) {
            $query->whereJsonContains('departure_cities', $fromFilter);
        }

        if (!empty($toFilter)) {
            $query->whereHas('destination', function($q) use ($toFilter) {
                $q->where('name', 'like', '%' . $toFilter . '%');
            });
        }

        // Date filter
        if (!empty($dateFilter)) {
            $query->whereDate('travel_date', $dateFilter);
        }

        // Month filter
        if (!empty($monthFilter)) {
            $query->whereMonth('travel_date', date('m', strtotime($monthFilter)));
        }

        // Handle price sorting
        if ($priceFilter === 'low_to_high') {
            $query->orderBy('price', 'asc');
        } elseif ($priceFilter === 'high_to_low') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $filteredPackages = $query->get();

        return view('frontend.agency', compact(
            'selectedPackage',
            'filteredPackages',
            'fromFilter',
            'toFilter',
            'dateFilter',
            'budgetFilter',
            'durationFilter',
            'monthFilter',
            'priceFilter',
            'agencyContacts'
        ));
    }

    private function getAgencyContacts()
    {
        return config('agencies.contacts', []);
    }
}