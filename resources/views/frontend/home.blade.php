@extends('layouts.frontend')

@section('title', 'ZUBEEE - Home')

@section('content')
<!-- Loading Screen -->
<div id="loadingScreen" class="fixed inset-0 bg-primary z-50 flex items-center justify-center">
    <div class="text-center">
        <!-- Logo with animation -->
        <div class="mb-6 animate-pulse">
            <img src="{{ $site_settings->get('site_logo') ? asset($site_settings->get('site_logo')) : asset('assets/images/logo1.png') }}" alt="{{ $site_settings->get('site_name') ?? 'ZUBEE' }} Logo" class="w-32 h-32 md:w-40 md:h-40 mx-auto">
        </div>

        <!-- Loading spinner -->
        <div class="flex justify-center mb-4">
            <div class="animate-spin rounded-full h-12 w-12 border-t-4 border-b-4 border-gold"></div>
        </div>
    </div>
</div>

<!-- Hero Banner with Swiper - Mobile Optimized -->
<section class="relative bg-gray-900 h-48 md:h-96 flex items-center mt-0">
    <div class="swiper heroSwiper w-full h-full">
        <div class="swiper-wrapper">
            @forelse($banners as $banner)
            <div class="swiper-slide">
                <div class="relative h-full bg-cover bg-center"
                    style="background-image: url('{{ asset('storage/' . $banner->image) }}')">
                    <div class="absolute inset-0 bg-black opacity-40"></div>
                    <div class="relative h-full flex items-center justify-center">
                        <div class="text-white text-center px-4 max-w-4xl mx-auto">
                            <h1 class="text-3xl md:text-6xl font-bold mb-4 animate-fade-in">{{ $banner->title }}</h1>
                            <p class="text-lg md:text-xl mb-8 animate-fade-in-delay">{{ $banner->description }}</p>
                            @if($banner->link)
                            <a href="{{ $banner->link }}" class="inline-block bg-secondary text-white px-8 py-3 rounded-lg hover:bg-gold transition duration-300 animate-bounce">
                                Explore Now
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <!-- Default Slide if no banners -->
            <div class="swiper-slide">
                <div class="relative h-full bg-cover bg-center"
                    style="background-image: url('https://nomadicweekends.com/blog/wp-content/uploads/2019/03/Lachung-City-In-between-the-Mountain-Ranges.jpg')">
                    <div class="absolute inset-0 bg-black opacity-40"></div>
                    <div class="relative h-full flex items-center justify-center">
                        <div class="text-white text-center px-4 max-w-4xl mx-auto">
                            <h1 class="text-3xl md:text-6xl font-bold mb-4 animate-fade-in">Tropical Paradise</h1>
                            <p class="text-lg md:text-xl mb-8 animate-fade-in-delay">Discover beautiful beaches and exotic destinations</p>
                            <a href="#search" class="inline-block bg-secondary text-white px-8 py-3 rounded-lg hover:bg-gold transition duration-300 animate-bounce">
                                Start Your Journey
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
        <!-- Pagination Dots -->
        <div class="swiper-pagination !bottom-8"></div>
        <!-- Navigation Arrows -->
        <div class="swiper-button-next !text-white !right-4"></div>
        <div class="swiper-button-prev !text-white !left-4"></div>
    </div>
</section>

<!-- Search Form Section - Mobile Optimized -->
<section id="search" class="bg-gradient-to-r from-gold/10 to-accent/10 py-4 border-b border-gold relative z-30">
    <div class="container mx-auto px-3">
        <div class="max-w-md mx-auto" data-aos="fade-down">
            <!-- Heading with Icon -->
            <div class="text-center mb-4">
                <div class="flex items-center justify-center mb-1">
                    <svg class="w-5 h-5 text-gold mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h2 class="font-bold text-forest" style="color: #a8894d; font-size: 18px;">Book Your Dream Vacation</h2>
                </div>
                <p class="text-[12px] text-gray-600">Find the perfect travel package for your next adventure</p>
            </div>

            <!-- Search Form -->
            <form method="GET" class="bg-white border border-gold p-4 rounded-xl shadow-sm relative z-30"
                id="searchForm">
                <input type="hidden" name="search" value="1">
                <div class="flex flex-col sm:flex-row gap-3 mb-3">
                    <!-- From Input -->
                    <div class="flex-1 relative text-left">
                        <label class="block text-[12px] font-bold text-forest mb-1 ml-1">From</label>
                        <div class="relative group">
                            <input type="text" name="from" id="fromInput" value="{{ $selectedFrom }}"
                                placeholder="Type or select departure city" autocomplete="off"
                                class="w-full bg-white border border-gold/40 rounded-lg px-3 py-1.5 text-[12px] focus:outline-none focus:border-gold transition-all appearance-none pr-8">
                            <div class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-gold/50 group-focus-within:text-gold transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            <!-- From Dropdown -->
                            <div id="fromDropdown" class="typeahead-dropdown absolute left-0 right-0 mt-1 bg-white border border-gold/30 rounded-lg shadow-xl hidden z-50 max-h-48 overflow-y-auto">
                                @foreach($dbDepartureCities as $city)
                                    <div class="typeahead-option px-3 py-2 hover:bg-gold/10 cursor-pointer text-forest text-xs font-medium border-b border-gold/5 last:border-0" data-value="{{ $city }}">
                                        {{ $city }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- To Input -->
                    <div class="flex-1 relative text-left">
                        <label class="block text-[12px] font-bold text-forest mb-1 ml-1">To</label>
                        <div class="relative group">
                            <input type="text" name="to" id="toInput" value="{{ $selectedTo }}"
                                placeholder="Type or select destination" autocomplete="off"
                                class="w-full bg-white border border-gold/40 rounded-lg px-3 py-1.5 text-[12px] focus:outline-none focus:border-gold transition-all appearance-none pr-8">
                            <div class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-gold/50 group-focus-within:text-gold transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            <!-- To Dropdown -->
                            <div id="toDropdown" class="typeahead-dropdown absolute left-0 right-0 mt-1 bg-white border border-gold/30 rounded-lg shadow-xl hidden z-50 max-h-48 overflow-y-auto">
                                @foreach($dbDestinations as $dest)
                                    <div class="typeahead-option px-3 py-2 hover:bg-gold/10 cursor-pointer text-forest text-xs font-medium border-b border-gold/5 last:border-0" data-value="{{ $dest }}">
                                        {{ $dest }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-[#a8894d] text-white px-4 py-2.5 rounded-lg font-bold hover:brightness-110 transition shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span class="text-[13px] uppercase tracking-wide">Search Travel Packages</span>
                </button>
            </form>

            <!-- Quick Search Examples -->
            <div class="mt-4 text-center">
                <p class="text-[12px] text-slate mb-2 font-medium">Popular searches:</p>
                <div class="flex flex-wrap justify-center gap-2">
                    @foreach ($popularSearches as $search)
                        <a href="?from={{ $search->from_city }}&to={{ $search->to_city }}&search=1"
                            class="bg-white border border-gold text-forest px-3 py-1 rounded-md text-[12px] font-medium hover:bg-gold hover:text-white transition-all shadow-sm">
                            @if ($search->from_city && $search->to_city)
                                {{ $search->from_city }} → {{ $search->to_city }}
                            @elseif ($search->from_city)
                                From {{ $search->from_city }}
                            @elseif ($search->to_city)
                                To {{ $search->to_city }}
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Rest of the CSS remains the same -->
<style>
    /* Enhanced Typeahead dropdown styles with proper z-index management */
    .typeahead-dropdown {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        z-index: 9999 !important;
    }

    .typeahead-option {
        border-bottom: 1px solid #f8f8f8;
        transition: all 0.2s ease;
    }

    .typeahead-option:last-child {
        border-bottom: none;
    }

    .typeahead-option:hover,
    .typeahead-option:focus {
        background-color: #dbb36320 !important;
        outline: none;
    }

    .typeahead-option.bg-secondary\/20 {
        background-color: #dbb36333 !important;
    }

    .typeahead-input:focus {
        border-color: #dbb363;
        box-shadow: 0 0 0 2px rgba(168, 137, 77, 0.1);
    }

    /* Scrollbar styling for dropdown */
    .typeahead-dropdown::-webkit-scrollbar {
        width: 6px;
    }

    .typeahead-dropdown::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 0 4px 4px 0;
    }

    .typeahead-dropdown::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    .typeahead-dropdown::-webkit-scrollbar-thumb:hover {
        background: #dbb363;
    }

    /* No results message */
    .no-results {
        font-style: italic;
        color: #888;
        text-align: center;
        border-top: 1px solid #f0f0f0;
    }

    /* Mobile optimizations */
    @media (max-width: 640px) {
        .typeahead-dropdown {
            max-height: 40vh !important;
        }
    }

    /* Z-index hierarchy fix */
    .bg-gradient-to-r {
        position: relative;
        z-index: 30;
    }

    .bg-background.border-b {
        position: relative;
        z-index: 10;
    }

    /* Hide scrollbar for stories */
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
        position: relative;
        z-index: 1;
    }

    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* Prevent body scroll when dropdown is open - Removed to prevent jump */
    /* body.dropdown-open {
        overflow: hidden !important;
        position: fixed;
        width: 100%;
        height: 100%;
    } */

    /* Loading Screen Styles */
    #loadingScreen {
        transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
    }

    #loadingScreen.fade-out {
        opacity: 0;
        visibility: hidden;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Swiper customization - Fixed */
    .heroSwiper {
        position: relative;
    }

    .heroSwiper .swiper-pagination {
        bottom: 10px !important;
    }

    .heroSwiper .swiper-pagination-bullet {
        background: white;
        opacity: 0.6;
        width: 8px;
        height: 8px;
        margin: 0 4px !important;
    }

    .heroSwiper .swiper-pagination-bullet-active {
        background: #dbb363;
        opacity: 1;
        transform: scale(1.2);
    }

    /* Hide navigation buttons */
    .heroSwiper .swiper-button-next,
    .heroSwiper .swiper-button-prev {
        display: none !important;
    }

    /* Mobile-first responsive design */
    @media (min-width: 640px) {
        .sm\:grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    /* Enhanced smooth scrolling */
    html {
        scroll-behavior: smooth !important;
        -webkit-overflow-scrolling: touch !important;
    }

    /* Card animations */
    .featured-card,
    .search-result-card {
        transition: all 0.3s ease;
    }

    .featured-card:hover,
    .search-result-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
    }

    /* Ensure proper spacing for mobile bottom navigation */
    @media (max-width: 768px) {
        .featured-section {
            padding-bottom: 80px;
        }

        
        /* Better mobile spacing */
        .container {
            padding-left: 12px;
            padding-right: 12px;
        }
    }

    /* Focus styles for accessibility */
    select:focus,
    button:focus {
        outline: 2px solid #dbb363;
        outline-offset: 2px;
    }

    /* Force smooth scrolling on all elements */
    * {
        scroll-behavior: smooth !important;
    }

    /* Shake animation for form validation */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    /* Story Viewer Modal */
    #storyViewerModal {
        z-index: 9999;
    }

    .story-progress-bar {
        height: 2px;
        background: rgba(255, 255, 255, 0.3);
        flex: 1;
        margin: 0 2px;
        border-radius: 1px;
        overflow: hidden;
    }

    .story-progress-fill {
        height: 100%;
        background: white;
        width: 0%;
        transition: width linear;
    }

    .story-content-item {
        display: none;
    }

    .story-content-item.active {
        display: block;
    }
</style>

<!-- Story Viewer Modal -->
<div id="storyViewerModal" class="fixed inset-0 bg-black z-[100] hidden flex items-center justify-center">
    <div class="relative w-full h-full md:max-w-md md:h-[80vh] bg-gray-900 md:rounded-2xl overflow-hidden shadow-2xl">
        <!-- Progress Bars Container -->
        <div id="progressBarsContainer" class="absolute top-4 left-4 right-4 z-20 flex gap-1">
            <!-- Progress bars injected via JS -->
        </div>

        <!-- Story Header -->
        <div class="absolute top-8 left-4 right-12 z-20 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full border border-white/50 overflow-hidden">
                <img id="storyHeaderImage" src="" class="w-full h-full object-cover">
            </div>
            <div>
                <h4 id="storyHeaderTitle" class="text-white text-xs font-bold leading-none"></h4>
                <p id="storyHeaderSubtitle" class="text-white/70 text-[10px] mt-0.5"></p>
            </div>
        </div>

        <!-- Close Button -->
        <button onclick="closeStoryViewer()" class="absolute top-8 right-4 z-30 text-white/80 hover:text-white transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>

        <!-- Navigation Areas -->
        <div class="absolute inset-0 z-10 flex">
            <div onclick="prevStorySlide()" class="w-1/3 h-full cursor-pointer"></div>
            <div onclick="nextStorySlide()" class="w-2/3 h-full cursor-pointer"></div>
        </div>

        <!-- Content Container -->
        <div id="storyContentContainer" class="w-full h-full flex items-center justify-center">
            <!-- Slides injected via JS -->
        </div>
    </div>
</div>

<!-- Stories Section - Mobile Optimized - Fixed z-index -->
<section class="py-3 bg-background border-b border-secondary relative z-10">
    <div class="container mx-auto px-2">
        <div class="flex space-x-4 overflow-x-auto pb-1 hide-scrollbar">
            <!-- Upcoming Travel Stories -->
            <!-- Upcoming Travel Stories -->
            @foreach ($upcomingTravels as $index => $travel)
                <div class="flex flex-col items-center flex-shrink-0 cursor-pointer story-trigger" 
                    data-aos="fade-up"
                    data-aos-delay="{{ ($index + 1) * 100 }}"
                    onclick="openStoryViewer({{ $index }})">
                    <div class="relative">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-r from-purple-400 to-pink-500 p-[2px]">
                            <div class="w-full h-full rounded-full p-[2px] bg-background">
                                <img src="{{ asset('storage/' . $travel->image) }}" alt="{{ $travel->destination }}"
                                    class="w-full h-full rounded-full object-cover">
                            </div>
                        </div>
                        <div
                            class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 bg-secondary text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold shadow-sm">
                            {{ $travel->date->format('M d') }}
                        </div>
                    </div>
                    <p class="mt-1.5 text-[10px] font-bold text-forest truncate max-w-[70px] uppercase tracking-tighter">{{ $travel->destination }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Search Results Section (Only shown when searching) -->
@if ($isSearching)
    <section class="py-4 bg-background relative z-0" id="search-results">
        <div class="container mx-auto px-2">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-text" data-aos="fade-up">
                    Search Results
                    @if (!empty($selectedFrom))
                        <span class="text-secondary">from {{ $selectedFrom }}</span>
                    @endif
                    @if (!empty($selectedTo))
                        <span class="text-secondary">to {{ $selectedTo }}</span>
                    @endif
                </h2>
                <span class="text-gray-500 text-sm">({{ count($searchResults) }} packages found)</span>
            </div>

            @if ($showNotFound)
                <!-- Not Found Message -->
                <div class="text-center py-8" data-aos="fade-up">
                    <div class="text-text mb-4">
                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-text mb-2">Search Not Available</h3>
                    <p class="text-gray-600 text-sm mb-4">
                        {{ $notFoundMessage }}
                    </p>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <p class="text-yellow-800 text-xs font-semibold mb-2">Available Options:</p>
                        <ul class="text-yellow-700 text-xs list-disc list-inside">
                            <li><strong>Departure Cities:</strong> {{ implode(', ', $dbDepartureCities) }}</li>
                            <li><strong>Destinations:</strong> {{ implode(', ', $dbDestinations) }}</li>
                        </ul>
                    </div>
                    <a href="{{ route('home') }}"
                        class="bg-secondary text-white px-6 py-3 rounded-lg font-semibold hover:bg-gold transition text-sm inline-block mb-24">
                        View Available Packages
                    </a>
                </div>
            @elseif (count($searchResults) > 0)
                <!-- 2 columns on mobile, 3 columns on larger screens -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 md:gap-4">
                    @foreach ($searchResults as $index => $package)
                        @php
                        // Set the appropriate link
                        $link = route('agency', ['package' => $package->name, 'id' => $package->id]);
                        @endphp

                        <a href="{{ $link }}"
                            class="block bg-background border border-secondary rounded-md shadow-sm overflow-hidden transition-transform hover:scale-[1.02] hover:shadow-md relative search-result-card"
                            data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                            <div class="relative">
                                <img src="{{ $package->image_url }}" alt="{{ $package->name }}"
                                    class="w-full h-20 sm:h-24 object-cover">
                                <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                    <h3 class="text-white font-bold text-xs text-center px-1 leading-tight">
                                        {{ $package->name }}
                                    </h3>
                                </div>
                            </div>
                            <!-- Package Details - Only shown in search results -->
                            <div class="p-2">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-secondary font-bold text-xs">₹{{ number_format($package->price) }}</span>
                                    <div class="flex items-center">
                                        <svg class="w-3 h-3 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span class="text-text text-xs">{{ $package->reviews->avg('rating') ?? 4.5 }}</span>
                                    </div>
                                </div>
                                <p class="text-gray-600 text-[10px] line-clamp-2">{{ $package->description }}</p>
                                <div class="mt-1 flex items-center text-gray-500 text-[10px]">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $package->destination->location }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Clear Search Button -->
                <div class="text-center mt-6 mb-24 pb-4" data-aos="fade-up">
                    <a href="{{ route('home') }}"
                        class="bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition text-sm inline-block">
                        Clear Search & Show All Packages
                    </a>
                </div>
            @else
                <!-- No Results Message -->
                <div class="text-center py-8" data-aos="fade-up">
                    <div class="text-text mb-4">
                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-text mb-2">No packages found</h3>
                    <p class="text-gray-600 text-sm mb-4">
                        @if (!empty($selectedFrom) && !empty($selectedTo))
                            No packages found from {{ $selectedFrom }} to {{ $selectedTo }}.
                        @elseif (!empty($selectedFrom))
                            No packages available from {{ $selectedFrom }}.
                        @elseif (!empty($selectedTo))
                            No packages found for destination {{ $selectedTo }}.
                        @endif
                    </p>
                    <p class="text-gray-500 text-xs mb-4">Try adjusting your search criteria or browse all packages.</p>
                    <a href="{{ route('home') }}"
                        class="bg-secondary text-white px-6 py-3 rounded-lg font-semibold hover:bg-gold transition text-sm inline-block">
                        View All Packages
                    </a>
                </div>
            @endif
        </div>
    </section>
@else
    <!-- Featured Packages - Simple Image Cards (Only shown when not searching) -->
    <section class="py-4 bg-background relative z-0" id="featured-packages">
        <div class="container mx-auto px-2">
            <h2 class="text-lg font-bold text-center mb-4 text-text" data-aos="fade-up">Featured Travel Packages</h2>

            <!-- 2 columns on mobile, 3 columns on larger screens - Simple image cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 md:gap-4">
                @foreach ($featuredPackages as $index => $package)
                    @php
                    // Determine if this card should be clickable (first 6 packages)
                    $isClickable = $index < 6;

                    // Set the appropriate link
                    if ($isClickable) {
                        $link = route('agency', ['package' => $package->name, 'id' => $package->id]);
                    }
                    @endphp

                    @if ($isClickable)
                        <a href="{{ $link }}"
                            class="block bg-background border border-secondary rounded-md shadow-sm overflow-hidden transition-transform hover:scale-[1.02] hover:shadow-md relative featured-card"
                            data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                    @else
                        <div class="bg-background border border-secondary rounded-md shadow-sm overflow-hidden transition-transform hover:scale-[1.02] hover:shadow-md relative featured-card"
                            data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                    @endif

                            <!-- Only show image and name for featured packages -->
                            <div class="relative">
                                <img src="{{ $package->image_url }}" alt="{{ $package->name }}"
                                    class="w-full h-20 sm:h-24 object-cover">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                    <h3 class="text-white font-bold text-xs text-center px-1 leading-tight">
                                        {{ $package->name }}
                                    </h3>
                                </div>
                            </div>

                    @if ($isClickable)
                        </a>
                    @else
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Fixed View All Packages Button with proper spacing - ALWAYS VISIBLE -->
            <div class="text-center mt-6 pb-20 md:pb-4" data-aos="fade-up">
                <a href="{{ route('search') }}"
                    class="bg-secondary text-white px-6 py-3 rounded-lg font-semibold hover:bg-gold transition text-sm inline-block">
                    View All Packages
                </a>
            </div>
        </div>
    </section>
@endif

@endsection

@push('scripts')
<script>
    // Enhanced Typeahead functionality with proper z-index and scroll management
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize both inputs
        initTypeahead('fromInput', 'fromDropdown');
        initTypeahead('toInput', 'toDropdown');

        function initTypeahead(inputId, dropdownId) {
            const input = document.getElementById(inputId);
            const dropdown = document.getElementById(dropdownId);
            const options = dropdown.querySelectorAll('.typeahead-option');
            let currentFocus = -1;

            // Show dropdown when input is focused
            input.addEventListener('focus', function () {
                filterOptions(input, dropdown, options);
                dropdown.classList.remove('hidden');
                currentFocus = -1;
                // document.body.classList.add('dropdown-open');
            });

            // Filter options based on input
            input.addEventListener('input', function () {
                filterOptions(input, dropdown, options);
                dropdown.classList.remove('hidden');
                currentFocus = -1;
            });

            // Select option from dropdown
            options.forEach(option => {
                option.addEventListener('click', function () {
                    input.value = this.getAttribute('data-value');
                    dropdown.classList.add('hidden');
                    currentFocus = -1;
                    // document.body.classList.remove('dropdown-open');
                    // Restore body scroll position
                    document.body.style.top = '';
                    document.body.style.position = '';
                });
            });

            // Keyboard navigation
            input.addEventListener('keydown', function (e) {
                const visibleOptions = Array.from(options).filter(option =>
                    option.style.display !== 'none'
                );

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    currentFocus = Math.min(currentFocus + 1, visibleOptions.length - 1);
                    setActive(visibleOptions, currentFocus);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    currentFocus = Math.max(currentFocus - 1, -1);
                    if (currentFocus === -1) {
                        removeActive(visibleOptions);
                        input.focus();
                    } else {
                        setActive(visibleOptions, currentFocus);
                    }
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (currentFocus > -1 && visibleOptions[currentFocus]) {
                        visibleOptions[currentFocus].click();
                    } else {
                        dropdown.classList.add('hidden');
                        // document.body.classList.remove('dropdown-open');
                        document.body.style.top = '';
                        document.body.style.position = '';
                    }
                } else if (e.key === 'Escape') {
                    dropdown.classList.add('hidden');
                    currentFocus = -1;
                    // document.body.classList.remove('dropdown-open');
                    document.body.style.top = '';
                    document.body.style.position = '';
                }
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    // Only perform actions if the dropdown is actually visible
                    if (!dropdown.classList.contains('hidden')) {
                        dropdown.classList.add('hidden');
                        currentFocus = -1;
                        // document.body.classList.remove('dropdown-open');
                        document.body.style.top = '';
                        document.body.style.position = '';
                        
                        // Restore scroll position logic would go here if needed, 
                        // but removing 'fixed' usually restores it if top wasn't modified with scroll offset.
                    }
                }
            });

            // Set active option
            function setActive(options, index) {
                removeActive(options);
                if (options[index]) {
                    options[index].classList.add('bg-secondary/20');
                    options[index].focus();
                    options[index].scrollIntoView({ block: 'nearest' });
                }
            }

            // Remove active state from all options
            function removeActive(options) {
                options.forEach(option => {
                    option.classList.remove('bg-secondary/20');
                });
            }
        }

        // Filter function
        function filterOptions(input, dropdown, options) {
            const searchTerm = input.value.toLowerCase();
            let hasVisibleOptions = false;

            options.forEach(option => {
                const value = option.getAttribute('data-value').toLowerCase();
                if (value.includes(searchTerm)) {
                    option.style.display = 'block';
                    hasVisibleOptions = true;
                } else {
                    option.style.display = 'none';
                }
            });

            // Show "No results" message if no options match
            let noResultsMsg = dropdown.querySelector('.no-results');
            if (!hasVisibleOptions && searchTerm.length > 0) {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.className = 'px-3 py-2 text-xs text-gray-500 no-results';
                    noResultsMsg.textContent = 'No matching results';
                    dropdown.appendChild(noResultsMsg);
                }
                noResultsMsg.style.display = 'block';
            } else if (noResultsMsg) {
                noResultsMsg.style.display = 'none';
            }
        }

        // Add keyboard navigation to options
        document.querySelectorAll('.typeahead-option').forEach(option => {
            option.setAttribute('tabindex', '0');

            option.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    const inputId = this.closest('.typeahead-dropdown').id === 'fromDropdown' ? 'fromInput' : 'toInput';
                    document.getElementById(inputId).value = this.getAttribute('data-value');
                    this.closest('.typeahead-dropdown').classList.add('hidden');
                    document.getElementById(inputId).focus();
                    document.body.classList.remove('dropdown-open');
                    document.body.style.top = '';
                    document.body.style.position = '';
                }
            });
        });

        // Close dropdowns on window resize
        window.addEventListener('resize', function () {
            document.querySelectorAll('.typeahead-dropdown').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
            document.body.classList.remove('dropdown-open');
            document.body.style.top = '';
            document.body.style.position = '';
        });

        // Hide loading screen after page loads
        window.addEventListener('load', function () {
            const loadingScreen = document.getElementById('loadingScreen');
            setTimeout(() => {
                loadingScreen.classList.add('fade-out');
                // Remove from DOM after fade out
                setTimeout(() => {
                    if (loadingScreen.parentNode) {
                        loadingScreen.remove();
                    }
                }, 500);
            }, 800);
        });

        // Initialize Hero Swiper with proper configuration
        const heroSwiper = new Swiper('.heroSwiper', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                dynamicBullets: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            // Enable keyboard control
            keyboard: {
                enabled: true,
            },
            // Enable mousewheel control
            mousewheel: false,
            speed: 800,
        });

        console.log('Hero Swiper initialized successfully');

        // Auto-scroll to search results when searching
        @if ($isSearching)
            setTimeout(function () {
                const searchResultsSection = document.getElementById('search-results');
                if (searchResultsSection) {
                    // Enhanced smooth scroll to search results
                    searchResultsSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    // Add subtle animation to search result cards
                    const searchCards = document.querySelectorAll('.search-result-card');
                    searchCards.forEach((card, index) => {
                        setTimeout(() => {
                            card.style.opacity = '0';
                            card.style.transform = 'translateY(20px)';
                            card.offsetHeight; // Trigger reflow
                            card.style.transition = 'all 0.6s ease';
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, index * 100);
                    });
                }
            }, 400);
        @endif

        // Enhanced form handling
        const searchForm = document.getElementById('searchForm');
        if (searchForm) {
            searchForm.addEventListener('submit', function (e) {
                const fromInput = this.querySelector('input[name="from"]');
                const toInput = this.querySelector('input[name="to"]');

                // If both fields are empty, prevent submission
                if (!fromInput.value && !toInput.value) {
                    e.preventDefault();
                    // Add shake animation to form
                    this.style.animation = 'shake 0.5s ease-in-out';
                    setTimeout(() => {
                        this.style.animation = '';
                    }, 500);
                    return false;
                }

                // Add loading state to button
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-white mx-auto"></div>';
                submitBtn.disabled = true;

                // Re-enable after 2 seconds (in case submission fails)
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 2000);
            });
        }

        // Add scroll-triggered animations for featured cards
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Story Viewer Logic
        const storiesData = @json($upcomingTravels);
        let currentStoryIndex = 0;
        let currentSlideIndex = 0;
        let storyInterval = null;
        const SLIDE_DURATION = 5000; // 5 seconds per slide

        window.openStoryViewer = function(index) {
            currentStoryIndex = index;
            currentSlideIndex = 0;
            const modal = document.getElementById('storyViewerModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            renderStory();
        };

        window.closeStoryViewer = function() {
            const modal = document.getElementById('storyViewerModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            clearInterval(storyInterval);
            const videos = modal.querySelectorAll('video');
            videos.forEach(v => {
                v.pause();
                v.currentTime = 0;
            });
        };

        function renderStory() {
            const story = storiesData[currentStoryIndex];
            const items = story.items && story.items.length > 0 ? story.items : [{file_path: story.image, type: 'image'}];
            
            // Header
            document.getElementById('storyHeaderImage').src = `{{ asset('storage/') }}/${story.image}`;
            document.getElementById('storyHeaderTitle').textContent = story.destination;
            document.getElementById('storyHeaderSubtitle').textContent = new Date(story.date).toLocaleDateString();

            // Progress Bars
            const progressContainer = document.getElementById('progressBarsContainer');
            progressContainer.innerHTML = '';
            items.forEach((_, i) => {
                const bar = document.createElement('div');
                bar.className = 'story-progress-bar';
                bar.innerHTML = '<div class="story-progress-fill"></div>';
                progressContainer.appendChild(bar);
            });

            // Items
            const contentContainer = document.getElementById('storyContentContainer');
            contentContainer.innerHTML = '';
            items.forEach((item, i) => {
                const itemDiv = document.createElement('div');
                itemDiv.className = `story-content-item h-full w-full ${i === 0 ? 'active' : ''}`;
                
                if (item.type === 'video') {
                    itemDiv.innerHTML = `
                        <video id="storyVideo-${i}" src="{{ asset('storage/') }}/${item.file_path}" 
                               class="w-full h-full object-contain" playsinline muted></video>
                    `;
                } else {
                    itemDiv.innerHTML = `
                        <img src="{{ asset('storage/') }}/${item.file_path}" 
                             class="w-full h-full object-contain">
                    `;
                }
                contentContainer.appendChild(itemDiv);
            });

            startSlide();
        }

        function startSlide() {
            clearInterval(storyInterval);
            const story = storiesData[currentStoryIndex];
            const items = story.items && story.items.length > 0 ? story.items : [{file_path: story.image, type: 'image'}];
            const currentItem = items[currentSlideIndex];
            
            updateProgressBars();

            if (currentItem.type === 'video') {
                const video = document.getElementById(`storyVideo-${currentSlideIndex}`);
                video.currentTime = 0;
                video.play();
                
                video.onended = () => nextStorySlide();
                
                // Track progress manually for video if needed, 
                // but for now just use duration or default
            }

            let start = Date.now();
            storyInterval = setInterval(() => {
                let elapsed = Date.now() - start;
                let pct = Math.min((elapsed / SLIDE_DURATION) * 100, 100);
                
                const fills = document.querySelectorAll('.story-progress-fill');
                if (fills[currentSlideIndex]) {
                    fills[currentSlideIndex].style.width = pct + '%';
                }

                if (elapsed >= SLIDE_DURATION) {
                    nextStorySlide();
                }
            }, 50);
        }

        function updateProgressBars() {
            const fills = document.querySelectorAll('.story-progress-fill');
            fills.forEach((fill, i) => {
                if (i < currentSlideIndex) fill.style.width = '100%';
                else if (i > currentSlideIndex) fill.style.width = '0%';
            });
        }

        window.nextStorySlide = function() {
            const story = storiesData[currentStoryIndex];
            const items = story.items && story.items.length > 0 ? story.items : [{file_path: story.image, type: 'image'}];
            
            if (currentSlideIndex < items.length - 1) {
                currentSlideIndex++;
                updateSlideView();
            } else if (currentStoryIndex < storiesData.length - 1) {
                currentStoryIndex++;
                currentSlideIndex = 0;
                renderStory();
            } else {
                closeStoryViewer();
            }
        };

        window.prevStorySlide = function() {
            if (currentSlideIndex > 0) {
                currentSlideIndex--;
                updateSlideView();
            } else if (currentStoryIndex > 0) {
                currentStoryIndex--;
                const prevStory = storiesData[currentStoryIndex];
                currentSlideIndex = (prevStory.items && prevStory.items.length > 0) ? prevStory.items.length - 1 : 0;
                renderStory();
            }
        };

        function updateSlideView() {
            const items = document.querySelectorAll('.story-content-item');
            items.forEach((item, i) => {
                item.classList.toggle('active', i === currentSlideIndex);
                const video = item.querySelector('video');
                if (video) {
                    video.pause();
                    video.currentTime = 0;
                }
            });
            startSlide();
        }

        // Observe all cards for scroll animations
        document.querySelectorAll('.featured-card, .search-result-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    });
</script>
@endpush
