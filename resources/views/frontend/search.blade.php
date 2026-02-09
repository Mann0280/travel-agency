@extends('layouts.frontend')

@section('title', 'ZUBEEE - Search Packages')

@section('content')
<style>
    .mobile-search-container {
        max-height: calc(100vh - 140px);
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 120px;
        scroll-behavior: smooth !important;
    }

    .mobile-search-container::-webkit-scrollbar {
        display: none;
    }

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
        color: #17320B;
    }

    .no-results {
        text-align: center;
        padding: 40px 20px;
        color: #6b7280;
    }

    .search-form-container {
        background: rgba(168, 137, 77, 0.05);
        border: 1px solid rgba(168, 137, 77, 0.2);
        border-radius: 12px;
        padding: 16px;
        margin: 16px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 500;
        color: #17320B;
        margin-bottom: 6px;
    }

    .form-select {
        width: 100%;
        padding: 12px;
        border: 1px solid #a8894d;
        border-radius: 8px;
        background: #ffffff;
        color: #17320B;
        font-size: 14px;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23a8894d' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px;
    }

    .form-select:disabled {
        background-color: #f9fafb;
        color: #6b7280;
        border-color: #d1d5db;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .search-button {
        width: 100%;
        background: #a8894d;
        color: #17320B;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .search-button:hover {
        background: #9d7c4f;
    }

    /* Improved Table Layout */
    .results-container {
        background: white;
    }

    .table-row {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid #e5e7eb;
        gap: 12px;
        transition: background-color 0.2s ease;
    }

    .table-row:hover {
        background-color: #f9fafb;
    }

    .package-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .package-info {
        flex: 1;
        min-width: 0;
    }

    .package-name {
        font-size: 16px;
        font-weight: 700;
        color: #17320B;
        margin-bottom: 2px;
        line-height: 1.2;
    }

    .package-agency {
        font-size: 14px;
        color: #a8894d;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .package-location {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 4px;
    }

    .package-date {
        font-size: 12px;
        color: #a8894d;
        font-weight: 500;
        background: rgba(168, 137, 77, 0.1);
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
    }

    .package-price-section {
        text-align: right;
        min-width: 100px;
        flex-shrink: 0;
    }

    .package-price {
        font-size: 18px;
        font-weight: 700;
        color: #17320B;
        display: block;
        line-height: 1.2;
    }

    .package-duration {
        font-size: 12px;
        color: #6b7280;
        display: block;
        margin-top: 2px;
    }

    .package-rating {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }

    .quick-search-examples {
        margin-top: 16px;
        text-align: center;
    }

    .quick-search-item {
        display: inline-block;
        background: #ffffff;
        border: 1px solid #a8894d;
        color: #17320B;
        padding: 6px 12px;
        border-radius: 16px;
        font-size: 11px;
        margin: 2px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .quick-search-item:hover {
        background: #a8894d;
        color: #ffffff;
    }

    .validation-error {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
        display: none;
    }

    /* Calendar Styles */
    .date-input {
        width: 100%;
        padding: 12px;
        border: 1px solid #a8894d;
        border-radius: 8px;
        background: #ffffff;
        color: #17320B;
        font-size: 14px;
        cursor: pointer;
    }

    .date-input:disabled {
        background-color: #f9fafb;
        color: #6b7280;
        border-color: #d1d5db;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .calendar-container {
        position: relative;
    }

    .calendar-popup {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #a8894d;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 1000;
        margin-top: 4px;
        padding: 16px;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .calendar-nav {
        background: none;
        border: none;
        color: #a8894d;
        font-size: 16px;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .calendar-title {
        font-weight: 600;
        color: #17320B;
        font-size: 14px;
    }

    .calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        margin-bottom: 8px;
    }

    .calendar-weekday {
        font-size: 12px;
        font-weight: 600;
        color: #a8894d;
        padding: 4px 0;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
    }

    .calendar-day {
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.2s ease;
        position: relative;
    }

    .calendar-day:hover {
        background: rgba(168, 137, 77, 0.1);
    }

    .calendar-day.other-month {
        color: #ccc;
        cursor: default;
    }

    .calendar-day.other-month:hover {
        background: transparent;
    }

    .calendar-day.selected {
        background: #a8894d;
        color: white;
    }

    .calendar-day.available {
        background: rgba(168, 137, 77, 0.1);
        border: 1px solid rgba(168, 137, 77, 0.3);
    }

    .calendar-day.available:hover {
        background: rgba(168, 137, 77, 0.2);
    }

    .calendar-day.today {
        border: 1px solid #a8894d;
    }

    .calendar-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #eee;
    }

    .calendar-btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        border: none;
        font-weight: 500;
    }

    .calendar-btn.today {
        background: rgba(168, 137, 77, 0.1);
        color: #a8894d;
    }

    .calendar-btn.cancel {
        background: #f3f4f6;
        color: #6b7280;
    }

    .calendar-btn.ok {
        background: #a8894d;
        color: white;
    }

    /* Desktop improvements */
    @media (min-width: 768px) {
        .table-row {
            padding: 16px 20px;
            gap: 16px;
        }
        
        .package-image {
            width: 70px;
            height: 70px;
        }
        
        .package-name {
            font-size: 18px;
        }
        
        .package-price {
            font-size: 20px;
        }
    }
</style>

<div class="min-h-screen bg-background text-text">
    <!-- Search Header (Mobile Style - Updated to Dark Green Theme) -->
    <div class="sticky top-0 z-40 bg-forest p-4 text-white shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="mr-3 p-2 rounded-full bg-white/10 hover:bg-white/20 transition">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <h1 class="text-lg font-bold text-white">Search Packages</h1>
            </div>
        </div>
    </div>

    <!-- Mobile Search Container -->
    <div class="mobile-search-container">
        <!-- Search Form Section -->
        @if (!$isSearching)
            <div class="search-form-container">
                <form method="GET" action="{{ route('search') }}" class="space-y-4" id="searchForm">
                    <!-- From -->
                    <div class="form-group">
                        <label class="form-label">From (Departure City) *</label>
                        <select name="from" class="form-select" required>
                            <option value="">Select departure city</option>
                            @foreach ($uniqueDepartureCities as $city)
                                <option value="{{ $city }}" {{ isset($fromFilter) && $fromFilter === $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                        <div class="validation-error" id="fromError">Please select a departure city</div>
                    </div>

                    <!-- To -->
                    <div class="form-group">
                        <label class="form-label">To (Destination)</label>
                        <select name="to" class="form-select">
                            <option value="">Any Destination</option>
                            @isset($allDestinations)
                                @foreach ($allDestinations as $destination)
                                    <option value="{{ $destination }}" {{ isset($toFilter) && $toFilter === $destination ? 'selected' : '' }}>
                                        {{ $destination }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <!-- Month -->
                    <div class="form-group">
                        <label class="form-label">Month</label>
                        <select name="month" class="form-select" id="monthSelect">
                            <option value="">Any Month</option>
                            @php
                                $uniqueMonths = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                            @endphp
                            @foreach ($uniqueMonths as $month)
                                <option value="{{ $month }}" {{ isset($monthFilter) && $monthFilter === $month ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date -->
                    <div class="form-group">
                        <label class="form-label">Specific Date</label>
                        <div class="calendar-container">
                            <input type="text" name="date_display" id="dateDisplay"
                                value="{{ htmlspecialchars($dateFilter ?? '') }}" class="date-input"
                                placeholder="Select travel date" readonly>
                            <input type="hidden" name="date" id="dateInput"
                                value="{{ htmlspecialchars($dateFilter ?? '') }}">

                            <!-- Calendar Popup -->
                            <div class="calendar-popup" id="calendarPopup" style="display: none;">
                                <div class="calendar-header">
                                    <button type="button" class="calendar-nav" id="prevMonth">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M15 18l-6-6 6-6" />
                                        </svg>
                                    </button>
                                    <div class="calendar-title" id="calendarTitle">December 2025</div>
                                    <button type="button" class="calendar-nav" id="nextMonth">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M9 18l6-6-6-6" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="calendar-weekdays">
                                    <div class="calendar-weekday">Sun</div>
                                    <div class="calendar-weekday">Mon</div>
                                    <div class="calendar-weekday">Tue</div>
                                    <div class="calendar-weekday">Wed</div>
                                    <div class="calendar-weekday">Thu</div>
                                    <div class="calendar-weekday">Fri</div>
                                    <div class="calendar-weekday">Sat</div>
                                </div>

                                <div class="calendar-days" id="calendarDays">
                                    <!-- Calendar days will be populated by JavaScript -->
                                </div>

                                <div class="calendar-actions">
                                    <button type="button" class="calendar-btn today" id="todayBtn">Today</button>
                                    <div>
                                        <button type="button" class="calendar-btn cancel" id="cancelBtn">Cancel</button>
                                        <button type="button" class="calendar-btn ok" id="okBtn">OK</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Budget -->
                    <div class="form-group">
                        <label class="form-label">Budget</label>
                        <select name="budget" class="form-select">
                            <option value="">Any Budget</option>
                            <option value="0-5000" {{ isset($budgetFilter) && ($budgetFilter === '0-5000' || $budgetFilter === '0-1000') ? 'selected' : '' }}>Under ₹5000</option>
                            <option value="5000-10000" {{ isset($budgetFilter) && ($budgetFilter === '5000-10000' || $budgetFilter === '1000-2000') ? 'selected' : '' }}>₹5000 - ₹10000</option>
                            <option value="10000-15000" {{ isset($budgetFilter) && ($budgetFilter === '10000-15000' || $budgetFilter === '2000-5000') ? 'selected' : '' }}>₹10000 - ₹15000</option>
                            <option value="15000+" {{ isset($budgetFilter) && $budgetFilter === '15000+' ? 'selected' : '' }}>Over ₹15000</option>
                        </select>
                    </div>

                    <!-- Duration -->
                    <div class="form-group">
                        <label class="form-label">Duration</label>
                        <select name="duration" id="durationSelect" class="form-select">
                            <option value="">Any Duration</option>
                            <option value="1-3" {{ isset($durationFilter) && $durationFilter === '1-3' ? 'selected' : '' }}>1-3 Days</option>
                            <option value="4-7" {{ isset($durationFilter) && $durationFilter === '4-7' ? 'selected' : '' }}>4-7 Days</option>
                            <option value="8-14" {{ isset($durationFilter) && $durationFilter === '8-14' ? 'selected' : '' }}>8-14 Days</option>
                            <option value="15+" {{ isset($durationFilter) && $durationFilter === '15+' ? 'selected' : '' }}>15+ Days</option>
                            <option value="custom" {{ isset($durationFilter) && $durationFilter === 'custom' ? 'selected' : '' }}>Custom Days</option>
                        </select>

                        <!-- Custom Duration Input -->
                        <div id="customDurationInput" class="mt-2" style="display: none;">
                            <input type="number" name="custom_duration"
                                value="{{ htmlspecialchars($customDurationFilter ?? '') }}" class="form-select"
                                placeholder="Enter number of days" min="1" max="30">
                        </div>
                    </div>

                    <!-- Search Button -->
                    <button type="submit" class="search-button" id="searchSubmitBtn">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search Packages
                    </button>
                </form>

                <!-- Quick Search Examples -->
                <div class="quick-search-examples">
                    <p class="text-xs text-gray-600 mb-2">Popular searches:</p>
                    <div>
                        @php
                        $popularSearches = [
                            ['from' => 'Ahmedabad', 'to' => 'Spiti'],
                            ['from' => 'Delhi', 'to' => 'Manali'],
                            ['from' => 'Mumbai', 'to' => 'Goa'],
                            ['from' => 'Bangalore', 'to' => 'Coorg'],
                            ['month' => 'December', 'budget' => '0-1000']
                        ];
                        @endphp
                        @foreach ($popularSearches as $search)
                            <a href="{{ route('search', $search + ['search' => 1]) }}" class="quick-search-item">
                                @php
                                $display = [];
                                if (isset($search['from'])) $display[] = $search['from'];
                                if (isset($search['to'])) $display[] = '→ ' . $search['to'];
                                if (isset($search['month'])) $display[] = '📅 ' . $search['month'];
                                if (isset($search['budget'])) $display[] = '💰 ' . $search['budget'];
                                echo implode(' ', $display);
                                @endphp
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Active Filters -->
        @php
        $activeFilters = [];
        if (!empty($fromFilter)) $activeFilters[] = 'From: ' . $fromFilter;
        if (!empty($toFilter)) $activeFilters[] = 'To: ' . $toFilter;
        if (!empty($monthFilter)) $activeFilters[] = 'Month: ' . $monthFilter;
        if (!empty($dateFilter)) $activeFilters[] = 'Date: ' . $dateFilter;
        if (!empty($budgetFilter)) $activeFilters[] = 'Budget: ' . $budgetFilter;
        if (!empty($durationFilter)) {
            if ($durationFilter === 'custom' && !empty($customDurationFilter)) {
                $activeFilters[] = 'Duration: ' . $customDurationFilter . ' days';
            } else {
                $activeFilters[] = 'Duration: ' . $durationFilter;
            }
        }
        @endphp

        @if (!empty($activeFilters))
            <div class="px-4 py-3 bg-secondary/10">
                <div class="flex flex-wrap gap-1">
                    @foreach ($activeFilters as $filter)
                        <span class="filter-chip active">{{ $filter }}</span>
                    @endforeach

                    <a href="{{ route('search') }}" class="filter-chip bg-red-500/10 border-red-500/30 text-red-600">
                        Clear All
                    </a>
                </div>
            </div>
        @endif

        <!-- Search Results -->
        <div class="search-results-section" id="searchResults">
            <div class="p-4 bg-white border-b">
                <div class="flex justify-between items-center">
                    <h2 class="text-base font-semibold text-text">
                        {{ $filteredPackages->count() }} Packages Found
                        @if (!empty($fromFilter) || !empty($toFilter))
                            <span class="text-sm text-gray-600 block mt-1">
                                @if (!empty($fromFilter)) from {{ $fromFilter }} @endif
                                @if (!empty($toFilter)) to {{ $toFilter }} @endif
                            </span>
                        @endif
                    </h2>
                    <div class="text-xs text-gray-600">
                        Sorted by: Popular
                    </div>
                </div>
            </div>

            @if ($filteredPackages->isEmpty())
                <div class="no-results">
                    <svg class="w-16 h-16 mx-auto text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-600 mb-2">No packages found matching your criteria</p>
                    @if (!empty($monthFilter) && !in_array($monthFilter, ['December', 'January']))
                        <p class="text-gray-500 text-sm mb-3">
                            No packages available for {{ $monthFilter }}. Packages are only available for December and January.
                        </p>
                    @endif
                    <a href="{{ route('search') }}" class="text-secondary text-sm font-medium">Clear filters and try again</a>
                </div>
            @else
                <!-- Improved Table Layout -->
                <div class="results-container">
                    @foreach ($filteredPackages as $package)
                        @php
                        // Get agency details safe access
                        $agencyName = $package->agency ? $package->agency->name : 'Unknown Agency';
                        $displayPrice = $package->price;
                        $displayDuration = $package->duration;
                        $displayDate = $package->travel_date ? $package->travel_date->format('j M') : '';
                        
                        $link = route('package.show', [$package->id]);
                        @endphp
                        <a href="{{ $link }}" class="card-link block">
                            <div class="table-row">
                                <!-- 1. Small Image -->
                                <img src="{{ $package->image && !str_starts_with($package->image, 'http') ? asset('storage/' . $package->image) : ($package->image ?? asset('assets/images/placeholder.jpg')) }}" 
                                     alt="{{ $package->name }}" class="package-image">
                                
                                <!-- 2. Place name with agency name -->
                                <div class="package-info">
                                    <div class="package-name">{{ $package->name }}</div>
                                    <div class="package-agency">by {{ $agencyName }}</div>
                                    <div class="package-location">{{ $package->location ?? ($package->destination ? $package->destination->name : '') }}</div>
                                    
                                    @if (!empty($displayDate))
                                        <div class="package-date">{{ $displayDate }}</div>
                                    @endif
                                    
                                    <div class="package-rating">
                                        @php
                                            $rating = $package->rating ?? 0;
                                            $fullStars = floor($rating);
                                        @endphp
                                        @for ($i = 0; $i < $fullStars; $i++) ★ @endfor
                                        @for ($i = $fullStars; $i < 5; $i++) ☆ @endfor
                                        <span>({{ number_format($rating, 1) }})</span>
                                    </div>
                                </div>
                                
                                <!-- 3. Rupees with duration -->
                                <div class="package-price-section">
                                    <div class="package-price">₹{{ number_format($displayPrice) }}</div>
                                    <div class="package-duration">{{ $displayDuration }}</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Show All Packages Button when searching -->
                @if ($isSearching)
                    <div class="text-center mt-6 pb-20 md:pb-4 button-container-mobile">
                        <a href="{{ route('search') }}"
                            class="bg-secondary text-white px-6 py-3 rounded-lg font-semibold hover:bg-gold transition text-sm inline-block">
                            Show All Packages
                        </a>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Calendar functionality
        const dateDisplay = document.getElementById('dateDisplay');
        const dateInput = document.getElementById('dateInput');
        const calendarPopup = document.getElementById('calendarPopup');
        const calendarTitle = document.getElementById('calendarTitle');
        const calendarDays = document.getElementById('calendarDays');
        const prevMonthBtn = document.getElementById('prevMonth');
        const nextMonthBtn = document.getElementById('nextMonth');
        const todayBtn = document.getElementById('todayBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const okBtn = document.getElementById('okBtn');
        const monthSelect = document.getElementById('monthSelect');

        // Define exact available dates
        const availableDates = [
            '2025-12-06', '2025-12-10', '2025-12-13', '2025-12-14', '2025-12-17',
            '2025-12-20', '2025-12-21', '2025-12-29', '2025-12-30', '2025-12-31',
            '2026-01-01', '2026-01-03'
        ];

        // Start with December 2025
        let currentDate = new Date(2025, 11, 1); // December 2025 (month is 0-indexed)
        let selectedDate = null;

        // Initialize calendar
        function initCalendar() {
            // Set initial display value
            if (dateInput.value) {
                selectedDate = new Date(dateInput.value);
                dateDisplay.value = formatDateDisplay(selectedDate);
                // Disable month select when date is selected
                disableMonthSelect();
            }
            
            // Render current month
            renderCalendar(currentDate);
            
            // Add event listeners
            dateDisplay.addEventListener('click', toggleCalendar);
            prevMonthBtn.addEventListener('click', goToPrevMonth);
            nextMonthBtn.addEventListener('click', goToNextMonth);
            todayBtn.addEventListener('click', goToToday);
            cancelBtn.addEventListener('click', cancelSelection);
            okBtn.addEventListener('click', confirmSelection);
            
            // Month select change listener
            monthSelect.addEventListener('change', function() {
                if (this.value) {
                    // If month is selected, clear and disable date
                    clearDateSelection();
                    disableDateInput();
                } else {
                    // If month is cleared, enable date
                    enableDateInput();
                }
            });
            
            // Close calendar when clicking outside
            document.addEventListener('click', function(e) {
                if (!dateDisplay.contains(e.target) && !calendarPopup.contains(e.target)) {
                    calendarPopup.style.display = 'none';
                }
            });
        }

        // Disable month select when date is selected
        function disableMonthSelect() {
            monthSelect.disabled = true;
            monthSelect.title = "Month selection disabled when specific date is selected";
        }

        // Enable month select
        function enableMonthSelect() {
            monthSelect.disabled = false;
            monthSelect.title = "";
        }

        // Disable date input when month is selected
        function disableDateInput() {
            dateDisplay.disabled = true;
            dateDisplay.title = "Date selection disabled when month is selected";
        }

        // Enable date input
        function enableDateInput() {
            dateDisplay.disabled = false;
            dateDisplay.title = "";
        }

        // Clear date selection
        function clearDateSelection() {
            selectedDate = null;
            dateInput.value = '';
            dateDisplay.value = '';
            // Re-render calendar to remove selection
            renderCalendar(currentDate);
        }

        // Toggle calendar visibility
        function toggleCalendar() {
            if (!dateDisplay.disabled) {
                calendarPopup.style.display = calendarPopup.style.display === 'none' ? 'block' : 'none';
            }
        }

        // Render calendar for a specific month
        function renderCalendar(date) {
            const year = date.getFullYear();
            const month = date.getMonth();
            
            // Update calendar title
            calendarTitle.textContent = `${getMonthName(month)} ${year}`;
            
            // Clear previous days
            calendarDays.innerHTML = '';
            
            // Get first day of month and number of days
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDay = firstDay.getDay();
            
            // Previous month days
            const prevMonthLastDay = new Date(year, month, 0).getDate();
            for (let i = 0; i < startingDay; i++) {
                const day = document.createElement('div');
                day.className = 'calendar-day other-month';
                day.textContent = prevMonthLastDay - startingDay + i + 1;
                calendarDays.appendChild(day);
            }
            
            // Current month days
            for (let i = 1; i <= daysInMonth; i++) {
                const day = document.createElement('div');
                day.className = 'calendar-day';
                day.textContent = i;
                const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                day.dataset.date = dateString;
                
                // Check if this date is available (only show specific dates)
                if (isDateAvailable(dateString)) {
                    day.classList.add('available');
                }
                
                // Check if this date is selected
                if (selectedDate && 
                    selectedDate.getFullYear() === year && 
                    selectedDate.getMonth() === month && 
                    selectedDate.getDate() === i) {
                    day.classList.add('selected');
                }
                
                // Add click event - only allow clicking on available dates
                day.addEventListener('click', function() {
                    if (!this.classList.contains('other-month') && this.classList.contains('available')) {
                        selectDate(this.dataset.date);
                    }
                });
                
                // Style unavailable dates differently
                if (!day.classList.contains('available') && !day.classList.contains('other-month')) {
                    day.style.opacity = '0.3';
                    day.style.cursor = 'not-allowed';
                }
                
                calendarDays.appendChild(day);
            }
            
            // Next month days
            const totalCells = 42; // 6 rows x 7 columns
            const remainingCells = totalCells - (startingDay + daysInMonth);
            
            for (let i = 1; i <= remainingCells; i++) {
                const day = document.createElement('div');
                day.className = 'calendar-day other-month';
                day.textContent = i;
                calendarDays.appendChild(day);
            }
        }

        // Check if a date is available
        function isDateAvailable(dateString) {
            return availableDates.includes(dateString);
        }

        // Select a date
        function selectDate(dateString) {
            selectedDate = new Date(dateString);
            
            // Update UI
            const allDays = calendarDays.querySelectorAll('.calendar-day');
            allDays.forEach(day => {
                day.classList.remove('selected');
                if (day.dataset.date === dateString) {
                    day.classList.add('selected');
                }
            });
            
            // Disable month select when date is selected
            disableMonthSelect();
        }

        // Navigate to previous month
        function goToPrevMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar(currentDate);
        }

        // Navigate to next month
        function goToNextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar(currentDate);
        }

        // Go to today - set to December 2025
        function goToToday() {
            currentDate = new Date(2025, 11, 1); // December 2025
            selectedDate = null;
            renderCalendar(currentDate);
        }

        // Cancel selection
        function cancelSelection() {
            calendarPopup.style.display = 'none';
        }

        // Confirm selection
        function confirmSelection() {
            if (selectedDate) {
                dateInput.value = formatDateInput(selectedDate);
                dateDisplay.value = formatDateDisplay(selectedDate);
                // Disable month select when date is confirmed
                disableMonthSelect();
            }
            calendarPopup.style.display = 'none';
        }

        // Helper functions
        function getMonthName(monthIndex) {
            const months = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            return months[monthIndex];
        }

        function formatDateDisplay(date) {
            return `${getMonthName(date.getMonth())} ${date.getDate()}, ${date.getFullYear()}`;
        }

        function formatDateInput(date) {
            return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
        }

        // Initialize the calendar
        initCalendar();

        // Duration select functionality
        const durationSelect = document.getElementById('durationSelect');
        const customDurationInput = document.getElementById('customDurationInput');

        if (durationSelect) {
            durationSelect.addEventListener('change', function () {
                if (this.value === 'custom') {
                    customDurationInput.style.display = 'block';
                } else {
                    customDurationInput.style.display = 'none';
                }
            });

            // Initialize on page load
            if (durationSelect.value === 'custom') {
                customDurationInput.style.display = 'block';
            }
        }

        // Form validation
        const searchForm = document.getElementById('searchForm');
        const searchSubmitBtn = document.getElementById('searchSubmitBtn');
        const fromError = document.getElementById('fromError');

        if (searchForm && searchSubmitBtn) {
            searchForm.addEventListener('submit', function (e) {
                let isValid = true;

                // Reset errors
                fromError.style.display = 'none';

                // Validate From field
                const fromSelect = document.querySelector('select[name="from"]');
                if (!fromSelect || !fromSelect.value) {
                    fromError.style.display = 'block';
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    return false;
                }

                // Add loading state to button
                const originalText = searchSubmitBtn.innerHTML;
                searchSubmitBtn.innerHTML = 'Searching...';
                searchSubmitBtn.disabled = true;

                // Re-enable after 3 seconds
                setTimeout(() => {
                    searchSubmitBtn.innerHTML = originalText;
                    searchSubmitBtn.disabled = false;
                }, 3000);
            });
        }

        // Auto-scroll to results if coming from search
        @if ($isSearching)
            setTimeout(function () {
                const resultsSection = document.getElementById('searchResults');
                if (resultsSection) {
                    resultsSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }, 300);
        @endif
    });
</script>
@endpush

@endsection
