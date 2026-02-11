<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Destination;
use App\Models\Review;
use App\Models\Banner;

use App\Models\PopularSearch;
use App\Models\Story;

class HomeController extends Controller
{
    /**
     * Redirect users to their respective dashboards.
     */
    public function dashboard()
    {
        if (auth('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        if (auth('agency')->check()) {
            return redirect()->route('agency.dashboard');
        }
        if (auth('web')->check()) {
            return redirect()->route('account');
        }
        return redirect()->route('login');
    }

    public function index(Request $request)
    {
        // Get featured packages
        $featuredPackages = Package::with(['destination', 'agency'])
            ->approved()
            ->where('is_featured', true)
            ->take(24)
            ->get();

        // Fetch all cities and destinations for dynamic search validation and UI
        $dbFromCities = Package::approved()->whereNotNull('from_city')->pluck('from_city')->unique()->toArray();
        $dbDepartureCitiesArray = Package::approved()->get()->flatMap(fn($p) => $p->departure_cities ?? [])->unique()->toArray();
        $dbDepartureCities = collect(array_merge($dbFromCities, $dbDepartureCitiesArray))->unique()->values()->all();
        $dbDestinations = Destination::whereHas('packages', fn($q) => $q->approved())->pluck('name')->unique()->values()->all();

        // Get upcoming travels (active stories)
        $upcomingTravels = Story::with(['items', 'user'])
            ->where('is_active', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('date', 'desc')
            ->get();

        // Get testimonials (reviews with high ratings and approved)
        $testimonials = Review::with(['user', 'package'])
            ->where('rating', '>=', 4)
            ->where('is_approved', true)
            ->take(5)
            ->get()
            ->map(function ($review) {
                return [
                    'name' => $review->user->name,
                    'review' => $review->comment,
                    'rating' => $review->rating,
                ];
            });

        // Get active banners
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Get popular searches
        $popularSearches = PopularSearch::orderBy('search_count', 'desc')->take(10)->get();

        // 4. Handle search form submission
        $selectedFrom = $request->input('from', '');
        $selectedTo = $request->input('to', '');
        $searchResults = collect();
        $isSearching = false;
        $showNotFound = false;
        $notFoundMessage = '';

        if (!empty($selectedFrom) || !empty($selectedTo)) {
            $isSearching = true;

            // Specific validation matching index.php (but now using DB lists)
            $isValidDeparture = false;
            if (!empty($selectedFrom)) {
                $isValidDeparture = collect($dbDepartureCities)->map(fn($c) => strtolower($c))->contains(strtolower($selectedFrom));
            } else {
                $isValidDeparture = true; // Skip if 'from' is empty
            }

            $isValidDestination = false;
            if (!empty($selectedTo)) {
                $isValidDestination = collect($dbDestinations)->map(fn($d) => strtolower($d))->contains(strtolower($selectedTo));
            } else {
                $isValidDestination = true; // Skip if 'to' is empty
            }

            // If "From" is valid OR "To" is valid, show results
            if ($isValidDeparture && $isValidDestination) {
                // Build query
                $query = Package::with(['destination', 'agency'])->approved();

                // Filter by departure city
                if (!empty($selectedFrom)) {
                    $query->where(function($q) use ($selectedFrom) {
                        $q->where('from_city', $selectedFrom)
                          ->orWhereJsonContains('departure_cities', $selectedFrom);
                    });
                }

                // Filter by destination
                if (!empty($selectedTo)) {
                    $query->whereHas('destination', function ($q) use ($selectedTo) {
                        $q->where('name', 'like', '%' . $selectedTo . '%');
                    });
                }

                $searchResults = $query->get();
                
                if ($searchResults->isEmpty()) {
                    $showNotFound = true;
                    $notFoundMessage = "No packages found for your search criteria.";
                }
            } else {
                $showNotFound = true;
                if (!empty($selectedFrom) && !$isValidDeparture) {
                    $notFoundMessage = "Departure city '{$selectedFrom}' is not available. Available departure cities: " . implode(', ', $dbDepartureCities) . ".";
                } elseif (!empty($selectedTo) && !$isValidDestination) {
                    $notFoundMessage = "Destination '{$selectedTo}' is not available. Available destinations: " . implode(', ', $dbDestinations) . ".";
                } else {
                    $notFoundMessage = "No packages found for your search criteria.";
                }
            }
        }

        return view('frontend.home', compact(
            'upcomingTravels',
            'featuredPackages',
            'searchResults',
            'isSearching',
            'selectedFrom',
            'selectedTo',
            'showNotFound',
            'notFoundMessage',
            'dbDepartureCities',
            'dbDestinations',
            'testimonials',
            'banners',
            'popularSearches'
        ));
    }
}