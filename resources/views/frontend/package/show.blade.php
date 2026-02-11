@extends('layouts.frontend')

@section('title', ($site_settings->get('site_name') ?? 'ZUBEEE') . ' - ' . $package->name)

@section('content')

<style>
    /* Custom styles for mobile optimization */
    .package-detail-container {
        /* Removed fixed height constraint for natural scrolling */
        min-height: 100vh;
        padding-bottom: 120px;
    }

    .tab-content {
        transition: all 0.3s ease;
    }

    .tab-content.active {
        display: block;
    }

    .tab-content.hidden {
        display: none;
    }

    /* Contact Action Buttons */
    .contact-action-buttons {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-top: 20px;
    }

    .contact-button {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px 20px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        border: none;
        cursor: pointer;
        width: 100%;
    }

    .contact-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
    }

    .call-button {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
    }

    .whatsapp-button {
        background: linear-gradient(135deg, #25d366, #128c7e);
        color: white;
    }

    .contact-button svg {
        width: 20px;
        height: 20px;
        margin-right: 10px;
    }

    /* Agency badge */
    .agency-badge {
        background: rgba(168, 137, 77, 0.2);
        border: 1px solid #a8894d;
        color: #a8894d;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 8px;
    }

    /* Hide scrollbar for horizontal tabs */
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* UPDATED: Horizontal scroll indicators */
    .tabs-container {
        position: relative;
        width: 100%;
        margin-bottom: 16px;
    }

    .tabs-scroll-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 0;
        background: transparent;
        padding: 8px 0;
    }

    /* UPDATED: Scroll arrows */
    .tabs-scroll-indicator {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 20;
        background: transparent;
        border: none;
        padding: 8px;
        cursor: pointer;
        transition: opacity 0.3s ease, transform 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .tabs-scroll-indicator.left {
        left: 0;
        background: linear-gradient(to right, var(--background, #ffffff) 60%, transparent);
        padding-left: 8px;
        padding-right: 12px;
    }

    .tabs-scroll-indicator.right {
        right: 0;
        background: linear-gradient(to left, var(--background, #ffffff) 60%, transparent);
        padding-right: 8px;
        padding-left: 12px;
    }

    .tabs-scroll-indicator.hidden {
        opacity: 0;
        pointer-events: none;
    }

    .tabs-scroll-indicator.visible {
        opacity: 1;
        pointer-events: auto;
    }

    .tabs-scroll-indicator svg {
        width: 20px;
        height: 20px;
        color: #a8894d;
        stroke-width: 2.5;
    }

    .tabs-scroll-indicator:hover {
        background: rgba(168, 137, 77, 0.1);
    }

    .tabs-scroll-indicator:active {
        transform: translateY(-50%) scale(0.95);
    }

    .tabs-scroll-content {
        display: flex;
        overflow-x: auto;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        padding: 4px 16px;
        scrollbar-width: none;
        -ms-overflow-style: none;
        gap: 8px;
    }

    .tabs-scroll-content::-webkit-scrollbar {
        display: none;
    }

    /* Tab button styles */
    .tab-button {
        flex-shrink: 0;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 25px;
        border: 2px solid #a8894d;
        background: white;
        color: #a8894d;
        transition: all 0.3s ease;
        white-space: nowrap;
        cursor: pointer;
    }

    .tab-button:hover {
        background: rgba(168, 137, 77, 0.1);
        transform: translateY(-1px);
    }

    .tab-button.active {
        background: #a8894d;
        color: white;
        box-shadow: 0 4px 12px rgba(168, 137, 77, 0.3);
    }
</style>

<div class="min-h-screen bg-background text-text">
    <!-- Header with back button -->
    <div class="sticky top-0 z-40 bg-primary p-4 flex items-center">
        <a href="javascript:history.back()" class="mr-3 p-2 rounded-full bg-secondary/20">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-lg font-bold truncate text-white">Package Details</h1>
    </div>

    <!-- Main Content -->
    <div class="package-detail-container">
        <!-- Package Image -->
        <div class="relative">
            <img src="{{ $package->image ? asset('storage/' . $package->image) : asset('assets/images/placeholder.jpg') }}" 
                 alt="{{ $package->name }}"
                 class="w-full h-48 object-cover">
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-primary to-transparent p-4">
                <h2 class="text-xl font-bold text-white">{{ $package->name }}</h2>
                <p class="text-gray-300 text-sm">{{ $package->location ?? $package->destination->name }}</p>
                <div class="agency-badge mt-2 bg-black/30 backdrop-blur-sm border-white/20 text-white">Booked with: {{ $package->agency->name }}</div>
            </div>
        </div>

        <!-- Package Info Cards -->
        <div class="p-4 space-y-4">
            <!-- Price & Rating Card -->
            <div class="bg-background border border-secondary rounded-lg p-4">
                <div class="flex justify-between items-center mb-3">
                    <div>
                        <div class="text-2xl font-bold text-secondary">
                            ₹{{ number_format($package->price) }}</div>
                        <div class="text-xs text-gray-600">per person</div>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center justify-end mb-1">
                            <div class="flex text-yellow-400 mr-2">
                                @php $rating = $package->rating ?? 4.8; @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= floor($rating) ? 'fill-current' : 'fill-gray-400' }}"
                                        viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-600">{{ $rating }}</span>
                        </div>
                        <div class="text-xs text-gray-600">{{ $package->reviews_count ?? 94 }} reviews</div>
                    </div>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-1 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $package->duration }}</span>
                    @if ($package->travel_date)
                        <span class="mx-2">•</span>
                        <svg class="w-4 h-4 mr-1 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>{{ $package->travel_date->format('d M, Y') }}</span>
                    @endif
                </div>
            </div>

            <!-- Description -->
            <div class="bg-background border border-secondary rounded-lg p-4">
                <h3 class="text-sm font-semibold text-text mb-2">About This Package</h3>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $package->description }}</p>
            </div>
        </div>

        <!-- Contact Action Buttons - Above Tabs -->
        <div class="px-4 mb-4">
            <div class="flex gap-3">
                <!-- WhatsApp Button -->
                <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $agencyPhone) }}?text={{ $whatsappMessage }}"
                    target="_blank"
                    onclick="trackButtonClick({{ $package->id }}, 'whatsapp')"
                    class="flex-1 flex items-center justify-center gap-2 bg-[#25d366] hover:bg-[#128c7e] text-white py-3 px-4 rounded-lg font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893c0-3.18-1.24-6.179-3.495-8.428" />
                    </svg>
                    <span>WhatsApp</span>
                </a>

                <!-- Call Button -->
                <a href="tel:+91{{ preg_replace('/[^0-9]/', '', $agencyPhone) }}"
                    onclick="trackButtonClick({{ $package->id }}, 'call')"
                    class="flex-1 flex items-center justify-center gap-2 bg-[#22c55e] hover:bg-[#16a34a] text-white py-3 px-4 rounded-lg font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                        </path>
                    </svg>
                    <span>Call Us</span>
                </a>
            </div>
        </div>

        <!-- Updated Tabs Navigation -->
        <div class="tabs-container">
            <!-- Left Arrow -->
            <button id="left-scroll-indicator" class="tabs-scroll-indicator left hidden">
                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <!-- Tabs content -->
            <div class="tabs-scroll-content hide-scrollbar" id="tabs-scroll-content">
                <button class="tab-button active" data-tab="itinerary">Itinerary</button>
                <button class="tab-button" data-tab="inclusion">Inclusion</button>
                <button class="tab-button" data-tab="exclusion">Exclusion</button>
                <button class="tab-button" data-tab="things-to-carry">Things to Carry</button>
                <button class="tab-button" data-tab="terms">Terms</button>
                <button class="tab-button" data-tab="contact">Contact</button>
            </div>

            <!-- Right Arrow -->
            <button id="right-scroll-indicator" class="tabs-scroll-indicator right">
                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>

        <!-- Tab Content -->
        <div class="p-4 tab-content-section pb-20">
            <!-- Itinerary Tab -->
            <div id="itinerary" class="tab-content active">
                <h2 class="text-lg font-bold text-text mb-4">Travel Itinerary</h2>
                <div class="space-y-4">
                    @foreach ($itinerary as $day)
                        @if(is_array($day))
                            <div class="border-l-2 border-secondary pl-3 py-1">
                                <h3 class="text-sm font-semibold text-text mb-2">{{ $day['day'] ?? 'Day' }}</h3>
                                <ul class="space-y-1 text-xs text-gray-600">
                                    @if(isset($day['activities']) && is_array($day['activities']))
                                        @foreach ($day['activities'] as $activity)
                                            <li class="flex items-start">
                                                <span class="text-secondary mr-2">•</span>
                                                <span>{{ $activity }}</span>
                                            </li>
                                        @endforeach
                                    @elseif(isset($day['description']))
                                        <li class="flex items-start">
                                            <span class="text-secondary mr-2">•</span>
                                            <span>{{ $day['description'] }}</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Inclusion Tab -->
            <div id="inclusion" class="tab-content hidden">
                <h2 class="text-lg font-bold text-text mb-4">What's Included</h2>
                <ul class="space-y-3">
                    @foreach ($inclusions as $inclusion)
                        <li class="flex items-start text-sm">
                            <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-600">{{ $inclusion }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Exclusion Tab -->
            <div id="exclusion" class="tab-content hidden">
                <h2 class="text-lg font-bold text-text mb-4">What's Not Included</h2>
                <ul class="space-y-3">
                    @foreach ($exclusions as $exclusion)
                        <li class="flex items-start text-sm">
                            <svg class="w-4 h-4 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-600">{{ $exclusion }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Things to Carry Tab -->
            <div id="things-to-carry" class="tab-content hidden">
                <h2 class="text-lg font-bold text-text mb-4">Things to Carry</h2>
                <div class="bg-secondary/10 border border-secondary/30 rounded-lg p-3 mb-4">
                    <p class="text-xs text-text">
                        <strong>Note:</strong> During Monsoon pack luggage in plastic bags. For camping, carry bedding
                        material as we provide tent & mattress only.
                    </p>
                </div>
                <ul class="grid grid-cols-2 gap-3">
                    @foreach ($thingsToCarry as $item)
                        <li class="flex items-start text-xs">
                            <svg class="w-3 h-3 text-blue-400 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-600">{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Terms & Conditions Tab -->
            <div id="terms" class="tab-content hidden">
                <h2 class="text-lg font-bold text-text mb-4">Terms & Conditions</h2>
                <ul class="space-y-3">
                    @foreach ($termsConditions as $term)
                        <li class="flex items-start text-sm">
                            <svg class="w-4 h-4 text-gray-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-600">{{ $term }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Contact Tab -->
            <div id="contact" class="tab-content hidden">
                <h2 class="text-lg font-bold text-text mb-4">Contact Us</h2>
                <div class="space-y-4">
                    <div class="flex gap-3 mb-4">
                        <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $agencyPhone) }}?text={{ $whatsappMessage }}" target="_blank"
                            onclick="trackButtonClick({{ $package->id }}, 'whatsapp')"
                            class="flex-1 flex items-center justify-center gap-2 bg-[#25d366] hover:bg-[#128c7e] text-white py-3 px-4 rounded-lg font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893c0-3.18-1.24-6.179-3.495-8.428" />
                            </svg>
                            <span>WhatsApp</span>
                        </a>
                        <a href="tel:+91{{ preg_replace('/[^0-9]/', '', $agencyPhone) }}"
                            onclick="trackButtonClick({{ $package->id }}, 'call')"
                            class="flex-1 flex items-center justify-center gap-2 bg-[#22c55e] hover:bg-[#16a34a] text-white py-3 px-4 rounded-lg font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>Call Us</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tab functionality
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', function () {
                const tabId = this.getAttribute('data-tab');

                // Remove active class from all buttons
                tabButtons.forEach(btn => {
                    btn.classList.remove('active');
                });

                // Hide all tab contents
                tabContents.forEach(content => {
                    content.classList.remove('active');
                    content.classList.add('hidden');
                });

                // Add active class to current button and content
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');
                document.getElementById(tabId).classList.remove('hidden');
            });
        });

        // Horizontal scroll functionality matching account page
        const tabsScrollContent = document.getElementById('tabs-scroll-content');
        const leftScrollIndicator = document.getElementById('left-scroll-indicator');
        const rightScrollIndicator = document.getElementById('right-scroll-indicator');

        function updateScrollIndicators() {
            const scrollLeft = tabsScrollContent.scrollLeft;
            const scrollWidth = tabsScrollContent.scrollWidth;
            const clientWidth = tabsScrollContent.clientWidth;
            
            // Show/hide left indicator
            if (scrollLeft > 10) {
                leftScrollIndicator.classList.remove('hidden');
            } else {
                leftScrollIndicator.classList.add('hidden');
            }
            
            // Show/hide right indicator
            if (scrollLeft + clientWidth < scrollWidth - 10) {
                rightScrollIndicator.classList.remove('hidden');
            } else {
                rightScrollIndicator.classList.add('hidden');
            }
        }

        // Scroll tabs function
        window.scrollTabs = function(scrollAmount) {
            tabsScrollContent.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        }

        // Add click events to arrows
        leftScrollIndicator.addEventListener('click', () => scrollTabs(-150));
        rightScrollIndicator.addEventListener('click', () => scrollTabs(150));

        // Initial check
        updateScrollIndicators();

        // Update indicators on scroll
        tabsScrollContent.addEventListener('scroll', updateScrollIndicators);

        // Update indicators on window resize
        window.addEventListener('resize', updateScrollIndicators);
    });

    // Button click tracking
    function trackButtonClick(packageId, buttonType) {
        fetch('/api/track-button-click', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                package_id: packageId,
                button_type: buttonType
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('✅ Click tracked:', buttonType, data);
        })
        .catch(error => {
            console.error('❌ Tracking error:', error);
        });
    }
</script>
@endsection
