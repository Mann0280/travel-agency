{{-- resources/views/account.blade.php --}}
@extends('layouts.frontend')

@section('title', 'ZUBEEE - My Account')

@section('content')
<div class="main-container">
    <!-- Toast Notification -->
    <div id="toastNotification" class="toast-notification success hidden">
        <svg class="toast-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span class="toast-message"></span>
    </div>

    <!-- Header - Simplified without logo -->
    <div class="header-section sticky top-0 z-40 bg-primary p-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="mr-3 p-1 rounded-full bg-secondary/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <h1 class="text-lg font-bold text-white">My Account</h1>
            </div>
            <div class="flex items-center space-x-3">
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="p-2 rounded-full bg-red-500/20 text-white hover:bg-red-500/40 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
                <div class="w-8 h-8 bg-secondary rounded-full flex items-center justify-center text-white text-sm font-bold">
                    {{ strtoupper(substr(Auth::guard('web')->user()->name ?? 'Guest', 0, 1)) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Tabs with Scroll Arrows -->
    <div class="navigation-tabs bg-white relative">
        <!-- Left Arrow -->
        <button id="scrollLeft" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-gradient-to-r from-white to-transparent pl-2 pr-4 py-2 hidden">
            <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <!-- Scrollable Tabs Container -->
        <div id="tabsContainer" class="flex overflow-x-auto mobile-nav px-3 py-2 space-x-1 scroll-smooth">
            <a href="{{ route('account', ['tab' => 'profile']) }}"
                class="tab-button flex-shrink-0 px-3 py-2 text-xs font-medium rounded-full border border-secondary {{ $currentTab == 'profile' ? 'active text-white' : 'text-text hover:bg-secondary/20' }}">
                Profile
            </a>
            <a href="{{ route('account', ['tab' => 'partner']) }}"
                class="tab-button flex-shrink-0 px-3 py-2 text-xs font-medium rounded-full border border-secondary {{ $currentTab == 'partner' ? 'active text-white' : 'text-text hover:bg-secondary/20' }}">
                Partner
            </a>
            <a href="{{ route('account', ['tab' => 'help']) }}"
                class="tab-button flex-shrink-0 px-3 py-2 text-xs font-medium rounded-full border border-secondary {{ $currentTab == 'help' ? 'active text-white' : 'text-text hover:bg-secondary/20' }}">
                Help
            </a>
            <a href="{{ route('account', ['tab' => 'feedback']) }}"
                class="tab-button flex-shrink-0 px-3 py-2 text-xs font-medium rounded-full border border-secondary {{ $currentTab == 'feedback' ? 'active text-white' : 'text-text hover:bg-secondary/20' }}">
                Feedback
            </a>
            <a href="{{ route('account', ['tab' => 'about']) }}"
                class="tab-button flex-shrink-0 px-3 py-2 text-xs font-medium rounded-full border border-secondary {{ $currentTab == 'about' ? 'active text-white' : 'text-text hover:bg-secondary/20' }}">
                About
            </a>
        </div>

        <!-- Right Arrow -->
        <button id="scrollRight" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-gradient-to-l from-white to-transparent pr-2 pl-4 py-2">
            <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>

    <!-- Main Content Container -->
    <div class="content-section">
        <div class="p-3">
            <!-- Profile Tab -->
            @if($currentTab == 'profile')
            <div id="profile" class="tab-content bg-white rounded-lg p-4">
                <h2 class="text-lg font-bold text-text mb-4">Profile Information</h2>

                <form id="profileForm" class="space-y-4" method="POST" action="{{ route('account.updateProfile') }}">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-text mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ auth()->user()->name ?? 'Guest' }}"
                            class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text">
                        <div class="text-red-500 text-xs mt-1 hidden" id="nameError"></div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-text mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ auth()->user()->email ?? '' }}"
                            class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text">
                        <div class="text-red-500 text-xs mt-1 hidden" id="emailError"></div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-text mb-1">Phone Number</label>
                        <input type="tel" name="phone" value="{{ $site_settings->get('phone') ?? '+1 234 567 8900' }}"
                            class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text">
                        <div class="text-red-500 text-xs mt-1 hidden" id="phoneError"></div>
                    </div>

                    <button type="submit" id="updateProfileBtn"
                        class="w-full bg-secondary text-white py-3 rounded-lg font-semibold text-sm hover:bg-gold transition">
                        Update Profile
                    </button>
                </form>

                <div class="mt-6 pt-4 border-t border-gray-200">
                    <h3 class="text-base font-bold text-text mb-3">Change Password</h3>
                    <form id="passwordForm" class="space-y-4" method="POST" action="{{ route('account.changePassword') }}">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-text mb-1">Current Password</label>
                            <input type="password" name="current_password"
                                class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text">
                            <div class="text-red-500 text-xs mt-1 hidden" id="currentPasswordError"></div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-text mb-1">New Password</label>
                            <input type="password" name="new_password"
                                class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text">
                            <div class="text-red-500 text-xs mt-1 hidden" id="newPasswordError"></div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-text mb-1">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation"
                                class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text">
                        </div>

                        <button type="submit" id="changePasswordBtn"
                            class="w-full bg-secondary text-white py-3 rounded-lg font-semibold text-sm hover:bg-gold transition">
                            Change Password
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Bookings Tab Removed -->
            @if(false)
            <div id="bookings" class="tab-content bg-white rounded-lg p-4" style="display:none;">
                <h2 class="text-lg font-bold text-text mb-4">My Bookings</h2>

                <div class="space-y-3">
                    @foreach($rawBookings as $index => $rawBooking)
                    <div class="border border-secondary rounded-lg p-3 gradient-bg">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-sm font-semibold text-text line-clamp-1">{{ $rawBooking->package->name ?? 'Package Deleted' }}</h3>
                            <span class="booking-status {{ $bookings[$index]['status_class'] }} rounded-full">{{ ucfirst($rawBooking->status) }}</span>
                        </div>
                        <p class="text-xs text-gray-600 mb-2">ID: ZB{{ str_pad($rawBooking->id, 6, '0', STR_PAD_LEFT) }} • {{ $rawBooking->package->duration ?? 'N/A' }}</p>
                        <div class="space-y-1 text-xs">
                            <div class="flex justify-between">
                                <span class="text-text">Travel Date:</span>
                                <span class="text-gray-600">{{ $rawBooking->travel_date ? $rawBooking->travel_date->format('M d, Y') : 'Flexible' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-text">Travelers:</span>
                                <span class="text-gray-600">{{ $rawBooking->travellers }} Adults</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-text">Total:</span>
                                <span class="text-secondary font-bold">₹{{ number_format($rawBooking->total_amount, 2) }}</span>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-3">
                            <button data-booking-id="{{ $rawBooking->id }}"
                                class="booking-details-btn flex-1 bg-secondary text-white text-center py-2 rounded text-xs font-medium hover:bg-gold transition">Details</button>
                            <a href="{{ route('account.booking.invoice', $rawBooking->id) }}" target="_blank"
                                onclick="showInvoiceAlert(event)"
                                class="invoice-download-btn flex-1 bg-gray-600 text-white text-center py-2 rounded text-xs hover:bg-gray-700 transition inline-block">Invoice</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Booking Details Modal -->
            <div id="bookingDetailsModal" class="modal-overlay modal-hidden">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="text-lg font-bold text-text">Booking Details</h3>
                        <button id="closeBookingModal" class="text-gray-600 hover:text-text">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="space-y-4">
                            <!-- Booking ID & Status -->
                            <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                                <div>
                                    <p class="text-xs text-gray-600">Booking ID</p>
                                    <p class="text-sm font-bold text-text" id="modal-booking-id"></p>
                                </div>
                                <span id="modal-status" class="booking-status rounded-full"></span>
                            </div>

                            <!-- Package Details -->
                            <div>
                                <h4 class="text-sm font-bold text-text mb-2">Package Information</h4>
                                <div class="space-y-2 text-xs">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Package:</span>
                                        <span class="text-text font-medium" id="modal-package-name"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Duration:</span>
                                        <span class="text-text" id="modal-duration"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Location:</span>
                                        <span class="text-text" id="modal-location"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Travel Details -->
                            <div>
                                <h4 class="text-sm font-bold text-text mb-2">Travel Information</h4>
                                <div class="space-y-2 text-xs">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Travel Date:</span>
                                        <span class="text-text" id="modal-travel-date"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Travelers:</span>
                                        <span class="text-text" id="modal-travelers"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div>
                                <h4 class="text-sm font-bold text-text mb-2">Contact Information</h4>
                                <div class="space-y-2 text-xs">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Name:</span>
                                        <span class="text-text" id="modal-customer-name"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Email:</span>
                                        <span class="text-text" id="modal-customer-email"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Phone:</span>
                                        <span class="text-text" id="modal-customer-phone"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Details -->
                            <div class="bg-gradient-bg p-3 rounded-lg border border-secondary">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-bold text-text">Total Amount:</span>
                                    <span class="text-lg font-black text-secondary" id="modal-total-amount"></span>
                                </div>
                            </div>

                            <!-- Special Requests -->
                            <div id="modal-special-requests-section" class="hidden">
                                <h4 class="text-sm font-bold text-text mb-2">Special Requests</h4>
                                <p class="text-xs text-gray-600" id="modal-special-requests"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Partner With Us Tab -->
            @if($currentTab == 'partner')
            <div id="partner" class="tab-content bg-white rounded-lg p-4">
                <h2 class="text-lg font-bold text-text mb-4">Partner With Us</h2>

                <div class="space-y-4 text-sm">
                    <p class="text-gray-600">{{ $partnerData['description'] ?? 'Join ZUBEEE\'s network of trusted travel partners.' }}</p>

                    @if(auth()->user()->agency)
                    <!-- User is already a partner -->
                    <div class="bg-forest/5 p-4 rounded-xl border border-forest/20 mb-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-forest/10 flex items-center justify-center text-forest">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-forest-dark">{{ auth()->user()->agency->name }}</h4>
                                <p class="text-[10px] text-slate font-bold uppercase tracking-wider">Active Partner</p>
                            </div>
                        </div>
                        <a href="{{ route('agency.dashboard') }}" 
                           class="w-full inline-flex items-center justify-center gap-2 bg-forest text-white py-2.5 rounded-lg text-sm font-bold hover:bg-forest-dark transition shadow-md">
                            Go to Agency Panel
                            <i class="fas fa-external-link-alt text-[10px]"></i>
                        </a>
                    </div>
                    @elseif(auth()->user()->partnerApplication)
                        @php
                            $application = auth()->user()->partnerApplication;
                        @endphp
                        
                        @if($application->status == 'pending')
                        <!-- Application is pending -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-clock text-yellow-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-yellow-900 mb-1">Application Under Review</h4>
                                    <p class="text-xs text-yellow-700 mb-2">Your partner application is being reviewed by our team. We'll notify you once it's processed.</p>
                                    <div class="text-xs text-yellow-600">
                                        <p><strong>Agency Name:</strong> {{ $application->agency_name }}</p>
                                        <p><strong>Submitted:</strong> {{ $application->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($application->status == 'rejected')
                        <!-- Application was rejected -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-times-circle text-red-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-red-900 mb-1">Application Not Approved</h4>
                                    <p class="text-xs text-red-700 mb-2">Unfortunately, your application was not approved at this time.</p>
                                    @if($application->admin_notes)
                                    <div class="bg-white rounded p-2 mb-2">
                                        <p class="text-xs text-gray-700"><strong>Reason:</strong> {{ $application->admin_notes }}</p>
                                    </div>
                                    @endif
                                    <p class="text-xs text-red-600">You can submit a new application below.</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif

                    @if(!auth()->user()->agency && (!auth()->user()->partnerApplication || auth()->user()->partnerApplication->status == 'rejected'))
                    <!-- Show application form -->
                    @if(!empty($partnerData['benefits']))
                    <div>
                        <h3 class="text-base font-semibold text-text mb-2">Benefits</h3>
                        <ul class="list-disc pl-4 text-gray-600 space-y-1 mb-4">
                            @foreach($partnerData['benefits'] as $benefit)
                                <li>{{ $benefit }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if(!empty($partnerData['requirements']))
                    <div>
                        <h3 class="text-base font-semibold text-text mb-2">Requirements</h3>
                        <ul class="list-disc pl-4 text-gray-600 space-y-1 mb-4">
                            @foreach($partnerData['requirements'] as $req)
                                <li>{{ $req }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Application Form -->
                    <div class="gradient-bg p-4 rounded-lg border border-secondary">
                        <h3 class="text-base font-semibold text-text mb-3">{{ $partnerData['apply_button_text'] ?? 'Apply Now' }}</h3>
                        
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-4 text-sm">
                                <ul class="list-disc pl-4">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('partner.apply') }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">Agency Name *</label>
                                <input type="text" name="agency_name" required
                                       class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">Business Email *</label>
                                <input type="email" name="business_email" required
                                       class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">Phone Number *</label>
                                <input type="tel" name="phone" required
                                       class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">Business Address *</label>
                                <textarea name="address" rows="2" required
                                          class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">Business Description * (min 10 characters)</label>
                                <textarea name="description" rows="3" required
                                          class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text"
                                          placeholder="Tell us about your travel agency..."></textarea>
                            </div>
                            <button type="submit"
                                    class="w-full bg-secondary text-white py-2 rounded text-sm font-medium hover:bg-gold transition">
                                Submit Application
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Help Center Tab -->
            @if($currentTab == 'help')
            <div id="help" class="tab-content bg-white rounded-lg p-4">
                <h2 class="text-lg font-bold text-text mb-4">Help Center</h2>

                <div class="space-y-4">
                    <div>
                        <h3 class="text-base font-semibold text-text mb-3">FAQ</h3>

                        <div class="space-y-3">
                            @forelse($faqs as $faq)
                            <div class="rounded-lg overflow-hidden faq-item">
                                <button
                                    class="faq-toggle w-full text-left px-4 py-3 text-sm font-medium text-text flex justify-between items-center bg-gray-50 hover:bg-gray-100 transition-colors">
                                    <span>{{ $faq->question }}</span>
                                    <svg class="w-4 h-4 text-gray-500 transition-transform duration-200 transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="faq-content px-4 py-3 bg-white text-gray-600 text-sm hidden border-t border-gray-100">
                                    {{ $faq->answer }}
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-sm italic">No FAQs available at the moment.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 mt-6">
                        <h3 class="text-base font-bold text-text mb-2">Need Help?</h3>
                        <p class="text-gray-600 text-sm mb-4">24/7 customer support available.</p>
                        <div class="flex gap-3">
                            <a href="tel:{{ $site_settings->get('phone') }}"
                                class="flex-1 bg-[#a8894d] text-white py-2.5 rounded text-sm font-medium text-center hover:bg-[#8f7543] transition shadow-sm">
                                Call
                            </a>
                            <a href="mailto:{{ $site_settings->get('contact_email') }}"
                                class="flex-1 bg-[#4b5563] text-white py-2.5 rounded text-sm font-medium text-center hover:bg-[#374151] transition shadow-sm">
                                Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>


                </div>
            </div>
            @endif

            <!-- Feedback Tab -->
            @if($currentTab == 'feedback')
            <div id="feedback" class="tab-content bg-white rounded-lg p-4">
                <h2 class="text-lg font-bold text-text mb-4">Share Feedback</h2>

                <form id="feedbackForm" class="space-y-4" method="POST" action="{{ route('account.submitFeedback') }}">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-text mb-2">Overall Rating</label>
                        <div class="flex justify-center space-x-1" id="starRating">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button"
                                    class="star-rating text-2xl text-yellow-400 hover:text-yellow-400 transition-colors"
                                    data-value="{{ $i }}" onclick="document.getElementById('ratingValue').value = {{ $i }}; updateStars({{ $i }})">★</button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" value="5">
                        <div class="text-red-500 text-xs mt-1 hidden" id="ratingError"></div>
                    </div>

                    <div x-data="{ 
                        open: false, 
                        selected: [],
                        get label() {
                            if (this.selected.length === 0) return 'Select Categories';
                            if (this.selected.length === 1) {
                                // Find the label for the first selected item
                                const checkbox = document.querySelector(`input[value='${this.selected[0]}']`);
                                return checkbox ? checkbox.closest('label').querySelector('span').textContent.trim() : '1 Category Selected';
                            }
                            return `${this.selected.length} Categories Selected`;
                        }
                    }">
                        <label class="block text-sm font-medium text-text mb-2">Category (Select one or more)</label>
                        <div class="relative">
                            <button type="button" @click="open = !open" 
                                class="w-full flex items-center justify-between form-input-mobile border border-secondary rounded-lg bg-white text-text text-sm text-left px-3 py-2.5 shadow-sm">
                                <span x-text="label" :class="selected.length === 0 ? 'text-gray-400' : 'text-text font-medium'"></span>
                                <svg class="w-4 h-4 text-secondary transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" 
                                class="absolute z-50 w-full mt-1 bg-white border border-secondary rounded-lg shadow-xl max-h-60 overflow-y-auto"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100">
                                <div class="p-2 space-y-1">
                                    @foreach($feedbackCategories as $cat)
                                        <label class="flex items-center px-3 py-2 rounded-md hover:bg-secondary/10 cursor-pointer transition-colors group">
                                            <input type="checkbox" name="category[]" value="{{ $cat->key }}" 
                                                class="w-4 h-4 rounded border-secondary text-secondary focus:ring-secondary mr-3"
                                                x-model="selected">
                                            <span class="text-sm text-text group-hover:text-secondary-dark">{{ $cat->label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="text-red-500 text-xs mt-1 hidden" id="categoryError"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Your Feedback</label>
                        <textarea name="feedback"
                            class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text text-sm"
                            rows="3"
                            placeholder="Share your experience..."></textarea>
                        <div class="text-red-500 text-xs mt-1 hidden" id="feedbackError"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-text mb-2">Recommend ZUBEEE?</label>
                        <div class="flex justify-between">
                            <label class="flex items-center text-text text-sm">
                                <input type="radio" name="recommend" value="yes" class="mr-2" checked>
                                <span>Yes</span>
                            </label>
                            <label class="flex items-center text-text text-sm">
                                <input type="radio" name="recommend" value="no" class="mr-2">
                                <span>No</span>
                            </label>
                            <label class="flex items-center text-text text-sm">
                                <input type="radio" name="recommend" value="maybe" class="mr-2">
                                <span>Maybe</span>
                            </label>
                        </div>
                        <div class="text-red-500 text-xs mt-1 hidden" id="recommendError"></div>
                    </div>

                    <button type="submit" id="submitFeedbackBtn"
                        class="w-full bg-secondary text-white py-3 rounded-lg font-semibold text-sm hover:bg-gold transition">
                        Submit Feedback
                    </button>
                </form>
            </div>
            @endif

            <!-- About Us Tab -->
            @if($currentTab == 'about')
            <div id="about" class="tab-content bg-white rounded-lg p-4">
                <h2 class="text-lg font-bold text-text mb-4">About ZUBEEE</h2>

                <div class="space-y-4 text-sm">
                    <div>
                        <h3 class="text-base font-semibold text-text mb-2">Our Mission</h3>
                        <p class="text-gray-600 text-sm">{{ $aboutData['mission'] ?? 'To inspire and enable people to explore the world.' }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-base font-semibold text-text mb-2">Who We Are</h3>
                        <p class="text-gray-600 text-sm">{{ $aboutData['description'] ?? 'We are a premier travel agency dedicated to creating unforgettable experiences.' }}</p>
                    </div>

                    @if(!empty($aboutData['values']))
                    <div>
                        <h3 class="text-base font-semibold text-text mb-2">Our Core Values</h3>
                        <ul class="list-disc pl-4 text-gray-600 space-y-1">
                            @foreach($aboutData['values'] as $value)
                                <li>{{ $value }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if(!empty($aboutData['why_choose']))
                    <div class="bg-secondary text-white p-4 rounded-lg">
                        <h3 class="text-base font-bold mb-3">Why Choose ZUBEEE?</h3>
                        <div class="space-y-2 text-sm">
                            @foreach($aboutData['why_choose'] as $reason)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ $reason }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Partner Application Modal -->
<div id="partnerModal" class="modal-overlay modal-hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-bold text-text">Partner Application</h3>
            <button id="closeModal" class="text-gray-600 hover:text-text">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="partnerForm" class="space-y-4" method="POST" action="{{ route('account.partnerApplication') }}">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-text mb-1">Full Name</label>
                    <input type="text" name="name" required value="{{ auth()->user()->name ?? 'Guest' }}"
                        class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text"
                        placeholder="Enter your full name">
                    <div class="text-red-500 text-xs mt-1 hidden" id="partnerNameError"></div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-1">Email Address</label>
                    <input type="email" name="email" required value="{{ auth()->user()->email ?? '' }}"
                        class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text"
                        placeholder="Enter your email">
                    <div class="text-red-500 text-xs mt-1 hidden" id="partnerEmailError"></div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-1">Contact Number</label>
                    <input type="tel" name="contact" required value="{{ $site_settings->get('phone') ?? '+1 234 567 8900' }}"
                        class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text"
                        placeholder="Enter your phone number">
                    <div class="text-red-500 text-xs mt-1 hidden" id="partnerContactError"></div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-1">Company Name</label>
                    <input type="text" name="company"
                        class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text"
                        placeholder="Enter company name (if applicable)">
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-1">Message</label>
                    <textarea name="message" rows="3"
                        class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text"
                        placeholder="Tell us about your business and why you want to partner with us"></textarea>
                    <div class="text-red-500 text-xs mt-1 hidden" id="partnerMessageError"></div>
                </div>

                <button type="submit" id="submitPartnerBtn"
                    class="w-full bg-secondary text-white py-3 rounded-lg font-semibold text-sm hover:bg-gold transition">
                    Submit Application
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --primary: #17320B;
        --secondary: #dbb363;
        --accent: #f3f4f6;
        --background: #ffffff;
        --text: #17320B;
    }

    body {
        background-color: var(--background);
        color: var(--text);
        font-family: 'Inter', sans-serif;
        overflow: hidden;
        height: 100vh;
    }

    .main-container {
        display: flex;
        flex-direction: column;
        height: 100vh;
    }

    .header-section {
        flex-shrink: 0;
    }

    .navigation-tabs {
        position: sticky;
        top: 0;
        z-index: 30;
        flex-shrink: 0;
    }

    .content-section {
        flex: 1;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 120px;
    }

    .content-section::-webkit-scrollbar {
        display: none;
    }

    .tab-button {
        transition: all 0.3s ease;
    }

    .tab-button.active {
        background: var(--secondary);
        color: var(--background);
    }

    .mobile-nav::-webkit-scrollbar {
        display: none;
    }

    .form-input-mobile {
        font-size: 14px;
        padding: 10px 12px;
    }

    .booking-status {
        font-size: 11px;
        padding: 4px 8px;
    }

    .package-card-mobile {
        transition: all 0.3s ease;
    }

    .package-card-mobile:hover {
        transform: translateY(-1px);
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 20px;
    }

    .modal-content {
        background: var(--background);
        border-radius: 12px;
        width: 100%;
        max-width: 400px;
        max-height: 90vh;
        overflow-y: auto;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }

    .modal-header {
        padding: 16px 20px;
        display: flex;
        justify-content: between;
        align-items: center;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-hidden {
        display: none;
    }

    /* Toast Notification Styles */
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #10b981;
        color: white;
        padding: 12px 16px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        z-index: 1001;
        display: flex;
        align-items: center;
        gap: 8px;
        transform: translateX(150%);
        transition: transform 0.3s ease-in-out;
        max-width: 300px;
    }

    .toast-notification.show {
        transform: translateX(0);
    }

    .toast-notification.success {
        background: #10b981;
    }

    .toast-notification.error {
        background: #ef4444;
    }

    .toast-icon {
        flex-shrink: 0;
    }

    .toast-message {
        font-size: 14px;
        font-weight: 500;
    }

    @media (max-width: 640px) {
        .toast-notification {
            top: -5px;
            right: 10px;
            left: 10px;
            max-width: none;
            transform: translateY(-100%);
        }

        .toast-notification.show {
            transform: translateY(0);
        }
        
        .content-section {
            padding-bottom: 140px;
        }
        
        .navigation-tabs {
            top: 0;
        }
    }

    /* Custom styles for better mobile experience */
    .line-clamp-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
    }

    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .bg-primary {
        background-color: var(--primary);
    }

    .bg-secondary {
        background-color: var(--secondary);
    }

    .bg-background {
        background-color: var(--background);
    }

    .text-primary {
        color: var(--primary);
    }

    .text-secondary {
        color: var(--secondary);
    }

    .text-text {
        color: var(--text);
    }

    .text-accent {
        color: var(--accent);
    }

    /* Gradient backgrounds matching index page */
    .gradient-bg {
        background: linear-gradient(135deg, rgba(168, 137, 77, 0.1) 0%, rgba(23, 50, 11, 0.05) 100%);
    }

    .header-bg {
        background: var(--primary);
    }

    /* Prevent horizontal scroll */
    html,
    body {
        max-width: 100%;
        overflow-x: hidden;
    }

    /* Smooth transitions for tab switching */
    .tab-content {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .tab-content.hidden {
        display: none;
        opacity: 0;
        transform: translateY(10px);
    }

    .tab-content:not(.hidden) {
        animation: fadeInUp 0.4s ease forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Smooth scrolling for FAQ toggles */
    .faq-content {
        transition: max-height 0.3s ease, opacity 0.3s ease;
        max-height: 0;
        opacity: 0;
        overflow: hidden;
    }

    .faq-content:not(.hidden) {
        max-height: 200px;
        opacity: 1;
    }

    /* Scroll arrow styles */
    #tabsContainer {
        scroll-behavior: smooth;
    }

    #scrollLeft, #scrollRight {
        transition: opacity 0.3s ease, transform 0.2s ease;
    }

    #scrollLeft:active, #scrollRight:active {
        transform: translateY(-50%) scale(0.95);
    }

    #scrollLeft {
        background: linear-gradient(to right, var(--background) 60%, transparent);
    }

    #scrollRight {
        background: linear-gradient(to left, var(--background) 60%, transparent);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Scroll Arrow Functionality
        const tabsContainer = document.getElementById('tabsContainer');
        const scrollLeftBtn = document.getElementById('scrollLeft');
        const scrollRightBtn = document.getElementById('scrollRight');

        function updateArrows() {
            if (!tabsContainer || !scrollLeftBtn || !scrollRightBtn) return;
            
            const scrollLeft = tabsContainer.scrollLeft;
            const maxScroll = tabsContainer.scrollWidth - tabsContainer.clientWidth;
            
            if (scrollLeft > 10) {
                scrollLeftBtn.classList.remove('hidden');
            } else {
                scrollLeftBtn.classList.add('hidden');
            }
            
            if (scrollLeft < maxScroll - 10) {
                scrollRightBtn.classList.remove('hidden');
            } else {
                scrollRightBtn.classList.add('hidden');
            }
        }

        if (scrollLeftBtn) {
            scrollLeftBtn.addEventListener('click', () => {
                tabsContainer.scrollBy({
                    left: -150,
                    behavior: 'smooth'
                });
            });
        }

        if (scrollRightBtn) {
            scrollRightBtn.addEventListener('click', () => {
                tabsContainer.scrollBy({
                    left: 150,
                    behavior: 'smooth'
                });
            });
        }

        if (tabsContainer) {
            tabsContainer.addEventListener('scroll', updateArrows);
        }
        window.addEventListener('resize', updateArrows);
        updateArrows();

        // FAQ toggle functionality with smooth animation
        document.querySelectorAll('.faq-toggle').forEach(button => {
            button.addEventListener('click', () => {
                const content = button.nextElementSibling;
                const icon = button.querySelector('svg');

                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    setTimeout(() => {
                        content.style.maxHeight = content.scrollHeight + 'px';
                    }, 10);
                } else {
                    content.style.maxHeight = '0';
                    setTimeout(() => {
                        content.classList.add('hidden');
                    }, 300);
                }

                if (icon) {
                    icon.classList.toggle('rotate-180');
                }
            });
        });

        // Star rating functionality
        const stars = document.querySelectorAll('.star-rating');
        const ratingValue = document.getElementById('ratingValue');
        
        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                // Remove error styling if present
                document.getElementById('ratingError').classList.add('hidden');
                
                // Update stars appearance
                stars.forEach((s, i) => {
                    if (i <= index) {
                        s.classList.remove('text-gray-400');
                        s.classList.add('text-yellow-400');
                    } else {
                        s.classList.remove('text-yellow-400');
                        s.classList.add('text-gray-400');
                    }
                });
                
                // Update hidden input value
                if (ratingValue) {
                    ratingValue.value = index + 1;
                }
            });
        });

        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toastNotification');
            const messageElement = toast.querySelector('.toast-message');

            if (!toast || !messageElement) return;

            messageElement.textContent = message;
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('show', type);
            }, 10);

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.classList.add('hidden');
                    toast.classList.remove(type);
                }, 300);
            }, 3000);
        }

        // Form submission with AJAX
        function submitForm(formId, successMessage) {
            const form = document.getElementById(formId);
            if (!form) return;

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Custom validation for rating in feedback form
                if (formId === 'feedbackForm') {
                    const rating = document.getElementById('ratingValue').value;
                    if (!rating || parseInt(rating) < 1) {
                        const errorEl = document.getElementById('ratingError');
                        errorEl.textContent = 'Please select a rating.';
                        errorEl.classList.remove('hidden');
                        return;
                    }
                }
                
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                // Show loading state
                submitBtn.innerHTML = 'Processing...';
                submitBtn.disabled = true;
                
                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        if (formId === 'feedbackForm') {
                            Swal.fire({
                                title: 'Feedback Recorded!',
                                text: 'Your feedback has been submitted successfully. Thank you for helping us improve!',
                                icon: 'success',
                                confirmButtonColor: '#17320b',
                                timer: 3000,
                                timerProgressBar: true,
                                customClass: {
                                    popup: 'rounded-2xl'
                                }
                            });
                        }
                        showToast(successMessage || data.message, 'success');
                        // Clear errors
                        form.querySelectorAll('.text-red-500').forEach(el => {
                            el.classList.add('hidden');
                        });
                        
                        // Reset form if needed
                        if (formId === 'partnerForm') {
                            document.getElementById('partnerModal').classList.add('modal-hidden');
                            document.body.style.overflow = 'auto';
                        }
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const errorElement = document.getElementById(`${field}Error`);
                                if (errorElement) {
                                    errorElement.textContent = data.errors[field][0];
                                    errorElement.classList.remove('hidden');
                                }
                            });
                        }
                        showToast(data.message || 'Something went wrong', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('An error occurred. Please try again.', 'error');
                } finally {
                    // Restore button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            });
        }

        // Initialize form submissions
        submitForm('profileForm', 'Profile updated successfully');
        submitForm('passwordForm', 'Password changed successfully');
        submitForm('feedbackForm', 'Feedback submitted successfully');
        submitForm('partnerForm', 'Application submitted successfully! We will contact you within 2 business days.');

        // Partner Application Modal Functionality
        const applyNowBtn = document.getElementById('applyNowBtn');
        const partnerModal = document.getElementById('partnerModal');
        const closeModal = document.getElementById('closeModal');

        if (applyNowBtn) {
            applyNowBtn.addEventListener('click', () => {
                if (partnerModal) {
                    partnerModal.classList.remove('modal-hidden');
                    document.body.style.overflow = 'hidden';
                }
            });
        }

        if (closeModal) {
            closeModal.addEventListener('click', () => {
                if (partnerModal) {
                    partnerModal.classList.add('modal-hidden');
                    document.body.style.overflow = 'auto';
                }
            });
        }

        if (partnerModal) {
            partnerModal.addEventListener('click', (e) => {
                if (e.target === partnerModal) {
                    partnerModal.classList.add('modal-hidden');
                    document.body.style.overflow = 'auto';
                }
            });
        }

        // Invoice Download Alert Function
        function showInvoiceAlert(event) {
            // Let the default link behavior happen (download will start)
            // Show alert after a short delay
            setTimeout(() => {
                Swal.fire({
                    title: 'Invoice Downloaded!',
                    text: 'Your invoice is being downloaded. Please check your downloads folder.',
                    icon: 'success',
                    confirmButtonColor: '#17320b',
                    confirmButtonText: 'OK',
                    timer: 3000
                });
            }, 500);
        }

        // Booking Details Modal Functionality
        const bookingDetailsModal = document.getElementById('bookingDetailsModal');
        const closeBookingModalBtn = document.getElementById('closeBookingModal');
        const bookingDetailsBtns = document.querySelectorAll('.booking-details-btn');

        // Booking data from server
        const bookingsData = @json($rawBookings);

        bookingDetailsBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const bookingId = parseInt(this.getAttribute('data-booking-id'));
                const booking = bookingsData.find(b => b.id === bookingId);
                
                if (booking) {
                    // Populate modal with booking data
                    document.getElementById('modal-booking-id').textContent = 'ZB' + String(booking.id).padStart(6, '0');
                    document.getElementById('modal-package-name').textContent = booking.package?.name || 'N/A';
                    document.getElementById('modal-duration').textContent = booking.package?.duration || 'N/A';
                    document.getElementById('modal-location').textContent = booking.package?.location || 'N/A';
                    document.getElementById('modal-travel-date').textContent = booking.travel_date || 'Flexible';
                    document.getElementById('modal-travelers').textContent = booking.travellers + ' Adults';
                    document.getElementById('modal-customer-name').textContent = booking.user?.name || '{{ auth()->user()->name }}';
                    document.getElementById('modal-customer-email').textContent = booking.user?.email || '{{ auth()->user()->email }}';
                    document.getElementById('modal-customer-phone').textContent = booking.phone || 'N/A';
                    document.getElementById('modal-total-amount').textContent = '₹' + Number(booking.total_amount).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    
                    // Set status
                    const statusEl = document.getElementById('modal-status');
                    statusEl.textContent = booking.status.charAt(0).toUpperCase() + booking.status.slice(1);
                    statusEl.className = 'booking-status rounded-full';
                    
                    // Add status class
                    if (booking.status === 'confirmed') {
                        statusEl.classList.add('bg-green-800', 'text-green-100');
                    } else if (booking.status === 'pending') {
                        statusEl.classList.add('bg-yellow-800', 'text-yellow-100');
                    } else if (booking.status === 'cancelled') {
                        statusEl.classList.add('bg-red-800', 'text-red-100');
                    } else {
                        statusEl.classList.add('bg-gray-800', 'text-gray-100');
                    }
                    
                    // Show/hide special requests
                    if (booking.special_requests) {
                        document.getElementById('modal-special-requests').textContent = booking.special_requests;
                        document.getElementById('modal-special-requests-section').classList.remove('hidden');
                    } else {
                        document.getElementById('modal-special-requests-section').classList.add('hidden');
                    }
                    
                    // Show modal
                    bookingDetailsModal.classList.remove('modal-hidden');
                }
            });
        });

        // Close modal
        if (closeBookingModalBtn) {
            closeBookingModalBtn.addEventListener('click', function() {
                bookingDetailsModal.classList.add('modal-hidden');
            });
        }

        // Close modal on overlay click
        if (bookingDetailsModal) {
            bookingDetailsModal.addEventListener('click', function(e) {
                if (e.target === bookingDetailsModal) {
                    bookingDetailsModal.classList.add('modal-hidden');
                }
            });
        }

        // Handle logout from mobile bottom nav
        const logoutForm = document.querySelector('form[action*="logout"]');
        if (logoutForm) {
            logoutForm.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to logout?')) {
                    e.preventDefault();
                }
            });
        }
    });
</script>
@endpush
