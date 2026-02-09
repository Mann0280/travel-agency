<!-- resources/views/layouts/header.blade.php -->
<!-- Desktop Header -->
<header class="desktop-nav bg-forest shadow-lg sticky top-0 z-50 transition-all duration-300">
    <div class="container mx-auto header-container header-minimal-padding flex justify-between items-center text-white">
        <!-- Left Navigation -->
        <nav class="hidden md:flex space-x-2 flex-1 justify-start">
            <a href="{{ route('home') }}" class="nav-link text-white hover:text-secondary transition font-medium {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('search') }}" class="nav-link text-white hover:text-secondary transition font-medium {{ request()->routeIs('search') ? 'active' : '' }}">Search</a>
            <a href="{{ route('account') }}" class="nav-link text-white hover:text-secondary transition font-medium {{ request()->routeIs('account') ? 'active' : '' }}">Account</a>
        </nav>

        <!-- Centered Logo - ENHANCED -->
        <div class="logo-container flex-1 flex justify-center">
            <a href="{{ route('home') }}" class="flex items-center no-underline group">
                <img src="{{ $site_settings->get('site_logo') ? asset($site_settings->get('site_logo')) : asset('assets/images/logo1.png') }}" alt="{{ $site_settings->get('site_name') ?? 'ZUBEEE Tours & Travels' }}" class="logo-image group-hover:brightness-110">
            </a>
        </div>

        <div class="hidden md:flex items-center space-x-3 flex-1 justify-end">
            @if(Auth::guard('web')->check())
                <span class="text-sm font-medium text-white">Welcome, {{ Auth::guard('web')->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="auth-btn auth-btn-secondary text-sm border-white text-white hover:bg-white hover:text-forest">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="auth-btn auth-btn-secondary text-sm text-white border-white hover:bg-white hover:text-forest">Login</a>
                <a href="{{ route('register') }}" class="auth-btn auth-btn-primary text-white text-sm">Register</a>
            @endif
        </div>

        <!-- Mobile Menu Button - ENHANCED -->
        <div class="md:hidden">
            <button id="menu-btn" class="hamburger focus:outline-none">
                <span class="hamburger-top"></span>
                <span class="hamburger-middle"></span>
                <span class="hamburger-bottom"></span>
            </button>
        </div>
    </div>

    <!-- Mobile Menu - Enhanced -->
    <div id="menu" class="md:hidden hidden fixed inset-x-0 top-20 bg-forest bg-opacity-95 backdrop-blur-md py-6 px-6 rounded-b-2xl border-t border-white border-opacity-20 transition-all duration-300">
        <div class="flex flex-col space-y-4">
            <a href="{{ route('home') }}" class="nav-link text-white hover:text-secondary transition py-3 font-medium text-center rounded-lg {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('search') }}" class="nav-link text-white hover:text-secondary transition py-3 font-medium text-center rounded-lg {{ request()->routeIs('search') ? 'active' : '' }}">Search</a>
            <a href="{{ route('account') }}" class="nav-link text-white hover:text-secondary transition py-3 font-medium text-center rounded-lg {{ request()->routeIs('account') ? 'active' : '' }}">Account</a>
            <div class="pt-4 border-t border-white border-opacity-30">
                @if(Auth::guard('web')->check())
                    <span class="text-sm font-medium py-2 block text-center text-white">Welcome, {{ Auth::guard('web')->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline-block w-full">
                        @csrf
                        <button type="submit" class="auth-btn auth-btn-secondary text-sm w-full mt-3 text-white border-white hover:bg-white hover:text-forest">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="auth-btn auth-btn-secondary text-sm text-white border-white hover:bg-white hover:text-forest block text-center mb-3">Login</a>
                    <a href="{{ route('register') }}" class="auth-btn auth-btn-primary text-white text-sm block text-center">Register</a>
                @endif
            </div>
        </div>
    </div>
</header>

<!-- Mobile Header - Enhanced -->
<header class="mobile-nav bg-forest shadow-lg sticky top-0 z-50 md:hidden transition-all duration-300">
    <div class="container mx-auto header-container mobile-header-minimal-padding flex justify-between items-center text-white">
        <!-- Left - Hidden on mobile -->
        <div class="mobile-header-left w-8"></div>

        <!-- Center - Enhanced Logo -->
        <div class="mobile-header-center">
            <a href="{{ route('home') }}" class="flex items-center no-underline group">
                <img src="{{ $site_settings->get('site_logo') ? asset($site_settings->get('site_logo')) : asset('assets/images/logo1.png') }}" alt="{{ $site_settings->get('site_name') ?? 'ZUBEEE Tours & Travels' }}" class="mobile-logo group-hover:brightness-110">
            </a>
        </div>

        <!-- Right - Enhanced Menu Button -->
        <div class="mobile-header-right">
            <button id="mobile-menu-btn" class="hamburger focus:outline-none">
                <span class="hamburger-top"></span>
                <span class="hamburger-middle"></span>
                <span class="hamburger-bottom"></span>
            </button>
        </div>
    </div>
</header>

<!-- Mobile Bottom Navigation - ENHANCED -->
<div class="mobile-nav fixed bottom-0 left-0 right-0 border-t border-gold/30 shadow-lg z-40 md:hidden mobile-bottom-nav transition-all duration-300" style="background-color: #17320b;">
    <div class="bottom-nav-container flex justify-between items-center w-full px-2 py-1">
        <a href="{{ route('home') }}" class="mobile-nav-item flex flex-col items-center py-3 px-4 rounded-xl transition-all duration-200 {{ request()->routeIs('home') ? 'active bg-gold bg-opacity-20' : 'hover:bg-white/10' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-[10px] font-medium text-white/70">Home</span>
        </a>
        <a href="{{ route('search') }}" class="mobile-nav-item flex flex-col items-center py-3 px-4 rounded-xl transition-all duration-200 {{ request()->routeIs('search') ? 'active bg-gold bg-opacity-20' : 'hover:bg-white/10' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span class="text-[10px] font-medium text-white/70">Search</span>
        </a>
        {{-- In the mobile bottom navigation section --}}
        <a href="{{ route('account') }}" class="mobile-nav-item flex flex-col items-center py-3 px-4 rounded-xl transition-all duration-200 {{ request()->routeIs('account') ? 'active bg-gold bg-opacity-20' : 'hover:bg-white/10' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-[10px] font-medium text-white/70">Account</span>
        </a>
        @if(Auth::guard('web')->check())
            <a href="#" id="mobile-logout-btn" class="mobile-nav-item flex flex-col items-center py-3 px-4 rounded-xl transition-all duration-200 hover:bg-red-500/10 text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="text-[10px] font-medium">Logout</span>
            </a>
            <form id="mobile-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        @endif
    </div>
</div>

<script>
    // Mobile menu toggle
    const btn = document.getElementById('menu-btn');
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('menu');

    function toggleMenu() {
        if (btn) btn.classList.toggle('open');
        if (mobileBtn) mobileBtn.classList.toggle('open');
        menu.classList.toggle('hidden');
        menu.classList.toggle('flex');
    }

    if (btn) btn.addEventListener('click', toggleMenu);
    if (mobileBtn) mobileBtn.addEventListener('click', toggleMenu);

    // Set active state for mobile bottom navigation
    document.addEventListener('DOMContentLoaded', function () {
        const currentRoute = "{{ request()->route()->getName() }}";
        const navItems = document.querySelectorAll('.mobile-nav-item');

        // Remove all active states first
        navItems.forEach(item => {
            item.classList.remove('active');
        });

        // Add active state based on current route
        navItems.forEach(item => {
            const href = item.getAttribute('href');
            const routeName = href.replace('{{ url("/") }}', '').replace(/^\//, '');
            
            if (routeName === '' && currentRoute === 'home') {
                item.classList.add('active');
            } else if (routeName === 'search' && currentRoute === 'search') {
                item.classList.add('active');
            } else if (routeName === 'account' && currentRoute === 'account') {
                item.classList.add('active');
            }
        });

        // Enhanced universal smooth scrolling initialization
        function initializeUniversalSmoothScrolling() {
            // Apply smooth scrolling to all scrollable elements
            const scrollableElements = document.querySelectorAll('body, html, div, section, main, .scroll-container, [class*="scroll"], [class*="container"]');

            scrollableElements.forEach(element => {
                element.style.scrollBehavior = 'smooth';
                element.style.webkitOverflowScrolling = 'touch';
            });

            // Force enable smooth scrolling at document level
            document.documentElement.style.scrollBehavior = 'smooth';
            document.body.style.scrollBehavior = 'smooth';
            document.documentElement.style.webkitOverflowScrolling = 'touch';
            document.body.style.webkitOverflowScrolling = 'touch';

            // Add smooth scroll class to all potentially scrollable containers
            const containers = document.querySelectorAll('.container, .mx-auto, .overflow-auto, .overflow-y-auto, .overflow-x-auto');
            containers.forEach(container => {
                container.classList.add('universal-smooth-scroll');
            });
        }

        // Initialize smooth scrolling
        initializeUniversalSmoothScrolling();

        // Re-initialize after a short delay to ensure all elements are loaded
        setTimeout(initializeUniversalSmoothScrolling, 100);
    });

    // Enhanced smooth scrolling for all links
    document.addEventListener('click', function (e) {
        if (e.target.tagName === 'A' || e.target.closest('a')) {
            const link = e.target.tagName === 'A' ? e.target : e.target.closest('a');
            const href = link.getAttribute('href');

            // Handle internal anchor links with smooth scrolling
            if (href && href.startsWith('#') && href.length > 1) {
                e.preventDefault();
                const targetElement = document.querySelector(href);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
            
            // Handle internal routes with hash fragments
            else if (href && href.includes('#')) {
                const [baseUrl, fragment] = href.split('#');
                if (fragment && (baseUrl === '' || baseUrl === window.location.pathname)) {
                    e.preventDefault();
                    const targetElement = document.querySelector('#' + fragment);
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            }
        }
    });

    // Mobile logout confirmation with SweetAlert
    const mobileLogoutBtn = document.getElementById('mobile-logout-btn');
    if (mobileLogoutBtn) {
        mobileLogoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Logout?',
                text: "Are you sure you want to end your session?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#17320b',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Logout',
                cancelButtonText: 'Cancel',
                background: '#ffffff',
                color: '#17320b',
                customClass: {
                    popup: 'rounded-2xl',
                    title: 'font-bold font-poppins',
                    container: 'font-poppins'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('mobile-logout-form').submit();
                }
            });
        });
    }
</script>
