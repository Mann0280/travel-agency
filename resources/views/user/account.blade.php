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
            <a href="{{ route('account', ['tab' => 'bookings']) }}"
                class="tab-button flex-shrink-0 px-3 py-2 text-xs font-medium rounded-full border border-secondary {{ $currentTab == 'bookings' ? 'active text-white' : 'text-text hover:bg-secondary/20' }}">
                Bookings
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
                        <input type="tel" name="phone" value="+1 234 567 8900"
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

            <!-- Bookings Tab -->
            @if($currentTab == 'bookings')
            <div id="bookings" class="tab-content bg-white rounded-lg p-4">
                <h2 class="text-lg font-bold text-text mb-4">My Bookings</h2>

                <div class="space-y-3">
                    @foreach($bookings as $booking)
                    <div class="border border-secondary rounded-lg p-3 gradient-bg">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-sm font-semibold text-text line-clamp-1">{{ $booking['title'] }}</h3>
                            <span class="booking-status {{ $booking['status_class'] }} rounded-full">{{ $booking['status'] }}</span>
                        </div>
                        <p class="text-xs text-gray-600 mb-2">ID: {{ $booking['id'] }} • {{ $booking['duration'] }}</p>
                        <div class="space-y-1 text-xs">
                            <div class="flex justify-between">
                                <span class="text-text">Travel Date:</span>
                                <span class="text-gray-600">{{ $booking['travel_date'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-text">Travelers:</span>
                                <span class="text-gray-600">{{ $booking['travelers'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-text">Total:</span>
                                <span class="text-secondary font-bold">{{ $booking['total'] }}</span>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-3">
                            <a href="#"
                                class="flex-1 bg-secondary text-white text-center py-2 rounded text-xs font-medium hover:bg-gold transition">Details</a>
                            <button
                                class="flex-1 bg-gray-600 text-white py-2 rounded text-xs hover:bg-gray-700 transition">Invoice</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Partner With Us Tab -->
            @if($currentTab == 'partner')
            <div id="partner" class="tab-content bg-white rounded-lg p-4">
                <h2 class="text-lg font-bold text-text mb-4">Partner With Us</h2>

                <div class="space-y-4 text-sm">
                    <p class="text-gray-600">Join ZUBEEE's network of trusted travel partners.</p>

                    @if(auth()->user()->agency)
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
                    @endif

                    <div>
                        <h3 class="text-base font-semibold text-text mb-2">Benefits</h3>
                        <ul class="list-disc pl-4 text-gray-600 space-y-1">
                            <li>Access to our customer base</li>
                            <li>Marketing support</li>
                            <li>Streamlined booking</li>
                            <li>Real-time management</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-base font-semibold text-text mb-2">Requirements</h3>
                        <ul class="list-disc pl-4 text-gray-600 space-y-1">
                            <li>Registered travel agency</li>
                            <li>Valid business license</li>
                            <li>2+ years operation</li>
                            <li>Positive reviews</li>
                        </ul>
                    </div>

                    <div class="gradient-bg p-4 rounded-lg border border-secondary">
                        <h3 class="text-base font-semibold text-text mb-2">Ready to Partner?</h3>
                        <p class="text-gray-600 text-sm mb-3">Apply now and we'll contact you within 2 days.</p>
                        <button id="applyNowBtn"
                            class="w-full bg-secondary text-white py-2 rounded text-sm font-medium hover:bg-gold transition">Apply
                            Now</button>
                    </div>
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

                        <div class="space-y-2">
                            @foreach($faqs as $faq)
                            <div class="border border-secondary rounded-lg gradient-bg">
                                <button
                                    class="faq-toggle w-full text-left p-3 text-sm font-medium text-text flex justify-between items-center">
                                    {{ $faq->title }}
                                    <svg class="w-4 h-4 text-gray-600 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="faq-content p-3 border-t border-secondary text-gray-600 text-sm hidden">
                                    {{ $faq->content }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="gradient-bg p-4 rounded-lg border border-secondary">
                        <h3 class="text-base font-semibold text-text mb-2">Need Help?</h3>
                        <p class="text-gray-600 text-sm mb-3">24/7 customer support available.</p>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="tel:+919499658115"
                                class="bg-secondary text-white py-2 rounded text-xs text-center hover:bg-gold transition">Call</a>
                            <a href="mailto:support@ZUBEEE.com"
                                class="bg-gray-600 text-white py-2 rounded text-xs text-center hover:bg-gray-700 transition">Email</a>
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
                                    data-value="{{ $i }}">★</button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" value="5">
                        <div class="text-red-500 text-xs mt-1 hidden" id="ratingError"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Category</label>
                        <select name="category"
                            class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text text-sm">
                            <option value="">Select Category</option>
                            @foreach($feedbackCategories as $cat)
                                <option value="{{ $cat->key }}">{{ $cat->label }}</option>
                            @endforeach
                        </select>
                        <div class="text-red-500 text-xs mt-1 hidden" id="categoryError"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Your Feedback</label>
                        <textarea name="feedback"
                            class="w-full form-input-mobile border border-secondary rounded-lg bg-white text-text text-sm"
                            rows="3"
                            placeholder="Share your experience...">I recently booked the Bali Tropical Escape and had an amazing time. The accommodations were excellent and the tour guides were very knowledgeable. The only suggestion I have is to include more vegetarian meal options.</textarea>
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
                    @foreach($aboutContent as $about)
                    <div>
                        <h3 class="text-base font-semibold text-text mb-2">{{ $about->title }}</h3>
                        <p class="text-gray-600 text-sm">{{ $about->content }}</p>
                    </div>
                    @endforeach


                    <div class="bg-secondary text-white p-4 rounded-lg">
                        <h3 class="text-base font-bold mb-3">Why Choose ZUBEEE?</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>25+ years experience</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>150+ destinations</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>10,000+ travelers</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>24/7 support</span>
                            </div>
                        </div>
                    </div>
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
                    <input type="tel" name="contact" required value="+1 234 567 8900"
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
