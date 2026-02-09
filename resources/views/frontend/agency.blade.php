@extends('layouts.frontend')

@section('title', 'ZUBEE - Travel Agencies')

@section('content')
<style>
    .mobile-agency-container {
        /* Removed fixed height to allow natural page scrolling */
        min-height: 100vh;
        padding-bottom: 80px;
    }

    .agency-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        background: white;
    }

    .agency-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.2);
    }

    /* Color scheme matching index.php */
    .bg-background {
        background-color: #ffffff !important;
    }

    .text-text {
        color: #17320b !important;
    }

    .text-accent {
        color: #17320b !important;
    }

    .bg-primary {
        background-color: #17320b !important;
    }

    .border-secondary {
        border-color: #a8894d !important;
    }

    .bg-secondary {
        background-color: #a8894d !important;
    }

    .gradient-bg {
        background: linear-gradient(135deg, rgba(168, 137, 77, 0.1) 0%, rgba(23, 50, 11, 0.05) 100%);
    }

    /* Active filter chips */
    .filter-chip {
        background: rgba(168, 137, 77, 0.1);
        border: 1px solid rgba(168, 137, 77, 0.3);
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 12px;
        color: #a8894d;
        display: inline-flex;
        align-items: center;
        margin: 2px;
    }

    .filter-chip.active {
        background: #a8894d;
        color: #17320b;
    }

    /* Improved table layout */
    .agency-table-row {
        display: flex;
        align-items: center;
        padding: 16px;
        border-bottom: 1px solid #e5e7eb;
        gap: 16px;
        transition: background-color 0.2s ease;
    }

    .agency-table-row:hover {
        background-color: #f9fafb;
    }

    .agency-info {
        flex: 1;
        min-width: 0;
    }

    .agency-name {
        font-size: 18px;
        font-weight: 700;
        color: #17320b;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .agency-date {
        font-size: 14px;
        color: #a8894d;
        font-weight: 500;
        background: rgba(168, 137, 77, 0.1);
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 6px;
    }

    .agency-price-section {
        text-align: right;
        min-width: 120px;
        flex-shrink: 0;
    }

    .agency-price {
        font-size: 20px;
        font-weight: 700;
        color: #17320b;
        display: block;
        line-height: 1.2;
    }

    .agency-duration {
        font-size: 14px;
        color: #6b7280;
        display: block;
        margin-top: 2px;
    }

    /* Responsive design */
    @media (max-width: 640px) {
        .agency-table-row {
            flex-direction: column;
            align-items: flex-start;
            padding: 12px;
            gap: 12px;
        }
        
        .agency-price-section {
            text-align: left;
            width: 100%;
        }
        
        .agency-name {
            font-size: 16px;
        }
        
        .agency-price {
            font-size: 18px;
        }
    }
</style>

<div class="min-h-screen bg-background text-text">
    <!-- Header -->
    <div class="sticky top-0 z-40 bg-primary p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="javascript:history.back()" class="mr-3 p-2 rounded-full bg-secondary/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <h1 class="text-lg font-bold text-white">
                    {{ $selectedPackage ? $selectedPackage->destination->name . ' - Agencies' : 'All Travel Agencies' }}
                </h1>
            </div>
        </div>
    </div>

    <!-- Mobile Agency Container -->
    <div class="mobile-agency-container universal-smooth-scroll ios-optimized">
        <div class="container mx-auto px-4 py-4">
            <!-- Active Search Filters -->
            @php
            $activeFilters = [];
            if (!empty($fromFilter)) $activeFilters[] = 'From: ' . $fromFilter;
            if (!empty($toFilter)) $activeFilters[] = 'To: ' . $toFilter;
            if (!empty($monthFilter)) $activeFilters[] = 'Month: ' . $monthFilter;
            if (!empty($dateFilter)) $activeFilters[] = 'Date: ' . \Carbon\Carbon::parse($dateFilter)->format('M j, Y');
            if (!empty($budgetFilter)) $activeFilters[] = 'Budget: ' . $budgetFilter;
            if (!empty($durationFilter)) $activeFilters[] = 'Duration: ' . $durationFilter;
            @endphp

            @if (!empty($activeFilters))
                <div class="mb-4 p-3 bg-secondary/10 rounded-lg">
                    <p class="text-sm font-medium text-text mb-2">Active Filters:</p>
                    <div class="flex flex-wrap gap-1">
                        @foreach ($activeFilters as $filter)
                            <span class="filter-chip active">{{ $filter }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Package Info (if specific package selected) -->
            @if ($selectedPackage)
                <div class="bg-background border border-secondary rounded-lg p-4 mb-4 gradient-bg">
                    <div class="flex items-center mb-3">
                        <img src="{{ asset('storage/' . $selectedPackage->image) }}" alt="{{ $selectedPackage->name }}"
                            class="w-16 h-16 object-cover rounded-lg mr-3">
                        <div>
                            <h2 class="text-lg font-bold text-text">{{ $selectedPackage->name }}</h2>
                            <p class="text-sm text-gray-600">{{ $selectedPackage->destination->name }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">{{ $selectedPackage->description }}</p>
                    
                    <!-- Package Rating (Mocked based on snippet - assuming logic exists or using placeholder) -->
                    {{-- Assuming average rating logic or placeholder as per snippet --}}
                   
                </div>
            @endif

            <!-- Header with Filter -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-text mb-2 sm:mb-0">
                        {{ $selectedPackage ? 'Available Agencies' : 'All Travel Agencies' }}
                    </h1>
                    <p class="text-sm text-gray-600">
                        {{ count($filteredPackages) }} agencies found
                        @if (!empty($activeFilters))
                            matching your criteria
                        @endif
                    </p>
                </div>

                <!-- Filter Dropdown -->
                <div class="flex items-center space-x-2 mt-2 sm:mt-0">
                    <span class="text-text text-sm font-medium">Sort by:</span>
                    <select id="priceFilter"
                        class="bg-background border border-secondary text-text px-3 py-2 rounded text-sm focus:outline-none focus:ring-1 focus:ring-secondary">
                        <option value="default" {{ $priceFilter === 'default' ? 'selected' : '' }}>Default</option>
                        <option value="low_to_high" {{ $priceFilter === 'low_to_high' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="high_to_low" {{ $priceFilter === 'high_to_low' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </div>
            </div>

            <!-- Agencies List -->
            @if (count($filteredPackages) > 0)
                <div class="space-y-3">
                    @foreach ($filteredPackages as $package)
                        <a href="{{ route('package.show', $package->id) }}"
                            class="block agency-card">
                            <div class="agency-table-row">
                                <!-- Agency Info -->
                                <div class="agency-info">
                                    <div class="agency-name">{{ $package->agency->name }}</div>
                                    @if ($package->travel_date)
                                        <div class="agency-date">{{ $package->travel_date->format('d M') }}</div>
                                    @endif
                                    <div class="text-sm text-gray-600">
                                        Contact: {{ $agencyContacts[$package->agency->name] ?? 'Not available' }}
                                    </div>
                                </div>

                                <!-- Price & Duration -->
                                <div class="agency-price-section">
                                    <div class="agency-price">₹{{ number_format($package->price) }}</div>
                                    <div class="agency-duration">{{ $package->duration }}</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-text mb-2">No agencies found</h3>
                    <p class="text-gray-600 text-sm mb-4">
                        @if (!empty($activeFilters))
                            No agencies match your current filters. Try adjusting your search criteria.
                        @else
                            There are no agencies available for this package.
                        @endif
                    </p>
                    <a href="{{ route('home') }}#search" class="text-secondary text-sm font-medium">Browse other packages</a>
                </div>
            @endif

            <!-- Back to Search Button -->
            <div class="text-center mt-6 mb-20">
                <a href="{{ route('home') }}#search" 
                    class="bg-secondary text-white px-6 py-3 rounded-lg font-semibold hover:bg-gold transition text-sm inline-block">
                    Back to Search
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Enhanced universal smooth scrolling initialization
        function initializeUniversalSmoothScrolling() {
            const scrollableElements = document.querySelectorAll('body, html, div, section, main, .scroll-container, [class*="scroll"], [class*="container"], .mobile-agency-container');

            scrollableElements.forEach(element => {
                element.style.scrollBehavior = 'smooth';
                element.style.webkitOverflowScrolling = 'touch';
            });

            document.documentElement.style.scrollBehavior = 'smooth';
            document.body.style.scrollBehavior = 'smooth';
            document.documentElement.style.webkitOverflowScrolling = 'touch';
            document.body.style.webkitOverflowScrolling = 'touch';

            const containers = document.querySelectorAll('.container, .mx-auto, .overflow-auto, .overflow-y-auto, .overflow-x-auto, .mobile-agency-container');
            containers.forEach(container => {
                container.classList.add('universal-smooth-scroll');
            });
        }

        // Initialize smooth scrolling
        initializeUniversalSmoothScrolling();
        setTimeout(initializeUniversalSmoothScrolling, 100);

        // Filter functionality
        document.getElementById('priceFilter').addEventListener('change', function () {
            const filterValue = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('filter', filterValue);
            window.location.href = url.toString();
        });

        // Add animation to agency cards
        const agencyCards = document.querySelectorAll('.agency-card');
        agencyCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';

            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endsection
