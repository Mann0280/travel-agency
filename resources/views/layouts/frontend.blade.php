<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $site_settings->get('site_favicon') ? asset($site_settings->get('site_favicon')) : ($site_settings->get('site_logo') ? asset($site_settings->get('site_logo')) : asset('favicon.png')) }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $site_settings->get('site_favicon') ? asset($site_settings->get('site_favicon')) : ($site_settings->get('site_logo') ? asset($site_settings->get('site_logo')) : asset('favicon.png')) }}">
    <link rel="apple-touch-icon" href="{{ $site_settings->get('site_favicon') ? asset($site_settings->get('site_favicon')) : ($site_settings->get('site_logo') ? asset($site_settings->get('site_logo')) : asset('assets/images/logo1.png')) }}">
    <link rel="shortcut icon" href="{{ $site_settings->get('site_favicon') ? asset($site_settings->get('site_favicon')) : ($site_settings->get('site_logo') ? asset($site_settings->get('site_logo')) : asset('assets/images/logo1.png')) }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', ($site_settings->get('meta_title') ?? $site_settings->get('site_name') ?? 'ZUBEEE'))</title>

    <meta name="description"
        content="{{ $site_settings->get('meta_description') ?? 'ZUBEEE - Your trusted partner for unforgettable travel experiences.' }}">

    <meta name="keywords" content="{{ $site_settings->get('meta_keywords') ?? 'travel, tours, vacation, packages, adventure, holiday' }}">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $site_settings->get('meta_title') ?? $site_settings->get('site_name') ?? 'ZUBEEE' }}">

    <meta property="og:description" content="{{ $site_settings->get('meta_description') ?? 'Your trusted partner for unforgettable travel experiences' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $site_settings->get('site_logo') ? asset($site_settings->get('site_logo')) : asset('assets/images/og-image.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&family=Playfair+Display:ital,wght@0,900;1,900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: 'var(--primary-color)',
                        gold: 'var(--secondary-color)',
                        champagne: '#fdfbf7',
                        slate: '#57688a',
                        primary: 'var(--primary-color)', // Legacy support
                        secondary: 'var(--secondary-color)', // Legacy support
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    boxShadow: {
                        'elite': '0 20px 40px rgba(23, 50, 11, 0.05)',
                    }
                }
            }
        }
    </script>
    
    <style>
        :root {
            --primary-color: {{ $site_settings->get('primary_color') ?? '#17320b' }};
            --secondary-color: {{ $site_settings->get('secondary_color') ?? '#a8894d' }};
        }
    </style>

    <!-- Swiper JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('custom.css') }}">

    @stack('styles')

    <style>
        body {
            background-color: #fdfbf7;
            color: var(--primary-color);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        .mobile-nav {
            display: none;
        }

        @media (max-width: 768px) {
            .desktop-nav {
                display: none;
            }

            .mobile-nav {
                display: flex;
            }

            /* Hide left and right sections on mobile */
            .mobile-header-left,
            .mobile-header-right {
                display: none;
            }

            /* Center the logo on mobile */
            .mobile-header-center {
                flex: 1;
                display: flex;
                justify-content: center;
            }
        }

        .hamburger {
            cursor: pointer;
            width: 24px;
            height: 24px;
            transition: all 0.25s;
            position: relative;
        }

        .hamburger-top,
        .hamburger-middle,
        .hamburger-bottom {
            position: absolute;
            top: 0;
            left: 0;
            width: 24px;
            height: 2px;
            background: var(--primary-color);
            transform: rotate(0);
            transition: all 0.5s;
        }

        .hamburger-middle {
            transform: translateY(7px);
        }

        .hamburger-bottom {
            transform: translateY(14px);
        }

        .open {
            transform: rotate(90deg);
            transform: translateY(0px);
        }

        .open .hamburger-top {
            transform: rotate(45deg) translateY(6px) translate(6px);
        }

        .open .hamburger-middle {
            display: none;
        }

        .open .hamburger-bottom {
            transform: rotate(-45deg) translateY(6px) translate(-6px);
        }

        /* Logo styling - SIGNIFICANTLY ENHANCED */
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin: 0;
        }

        /* Desktop Logo - ENHANCED SIZE AND STYLING */
        .logo-image {
            height: 140px;
            width: auto;
            object-fit: contain;
            transition: all 0.3s ease;
            display: block;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .logo-image:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.15));
        }

        /* Mobile Logo - ENHANCED SIZE AND STYLING */
        .mobile-logo {
            height: 100px;
            width: auto;
            object-fit: contain;
            display: block;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        /* Enhanced Navigation Links */
        .nav-link {
            position: relative;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            background-color: rgba(168, 137, 77, 0.1);
            transform: translateY(-1px);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: var(--secondary-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        /* Enhanced Auth Buttons */
        .auth-btn {
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .auth-btn-primary {
            background: linear-gradient(135deg, var(--secondary-color), #8b6a42);
            border-color: var(--secondary-color);
        }

        .auth-btn-primary:hover {
            background: linear-gradient(135deg, #8b6a42, #7a5a31);
            border-color: #8b6a42;
        }

        .auth-btn-secondary {
            border-color: var(--secondary-color);
            color: var(--secondary-color);
        }

        .auth-btn-secondary:hover {
            background-color: var(--secondary-color);
            color: white;
        }

        /* Enhanced Hamburger Menu */
        .hamburger {
            cursor: pointer;
            width: 30px;
            height: 24px;
            transition: all 0.3s ease;
            position: relative;
            background: transparent;
            border: none;
            padding: 0;
        }

        .hamburger span {
            position: absolute;
            left: 0;
            width: 30px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .hamburger .hamburger-top {
            top: 0;
        }

        .hamburger .hamburger-middle {
            top: 10px;
        }

        .hamburger .hamburger-bottom {
            top: 20px;
        }

        .hamburger.open .hamburger-top {
            transform: rotate(45deg) translateY(10px) translateX(6px);
        }

        .hamburger.open .hamburger-middle {
            opacity: 0;
        }

        .hamburger.open .hamburger-bottom {
            transform: rotate(-45deg) translateY(-10px) translateX(6px);
        }
        .mobile-nav-item {
            color: #57688a;
            /* Gray by default */
            transition: all 0.3s ease;
        }

        .mobile-nav-item svg {
            stroke: #57688a;
            /* Gray by default */
            transition: all 0.3s ease;
        }

        .mobile-nav-item.active {
            color: #dbb363 !important;
            /* Gold when active */
        }

        .mobile-nav-item.active svg {
            stroke: #dbb363 !important;
            /* Gold when active */
        }

        .mobile-nav-item:hover {
            color: #dbb363;
        }

        .mobile-nav-item:hover svg {
            stroke: #dbb363;
        }

        /* Enhanced mobile bottom navigation */
        .mobile-bottom-nav {
            padding-bottom: env(safe-area-inset-bottom);
            backdrop-filter: blur(10px);
        }

        .mobile-nav-item span {
            font-weight: 500;
            transition: all 0.3s ease;
        }

        /* Header background transitions */
        header {
            transition: all 0.3s ease;
        }

        /* Smooth transitions for all header elements */
        .nav-link,
        .auth-btn,
        .hamburger,
        .logo-image {
            transition: all 0.3s ease;
        }

        /* WhatsApp-like bottom navigation styling */
        .bottom-nav-container {
            width: 100%;
            max-width: 100%;
            margin: 0;
        }

        /* MINIMIZED header padding and margin - IMPROVED */
        .header-minimal-padding {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            margin: 0;
        }

        .mobile-header-minimal-padding {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            margin: 0;
        }

        /* Remove any default margins */
        header {
            margin: 0 !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Ensure container has proper spacing */
        .header-container {
            padding-left: 2rem;
            padding-right: 2rem;
            margin: 0;
            max-width: 1200px;
        }

        /* Enhanced smooth scrolling */
        html {
            scroll-behavior: smooth !important;
            -webkit-overflow-scrolling: touch !important;
        }

        /* * {
            scroll-behavior: smooth !important;
        } */

        body {
            scroll-behavior: smooth !important;
            -webkit-overflow-scrolling: touch !important;
            overflow-x: hidden;
        }

        /* Universal smooth scrolling class */
        .universal-smooth-scroll {
            scroll-behavior: smooth !important;
            -webkit-overflow-scrolling: touch !important;
        }

        /* Hero animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 1s ease-out;
        }

        .animate-fade-in-delay {
            animation: fadeIn 1s ease-out 0.5s both;
        }

        /* Swiper customizations for full screen */
        .swiper-button-next,
        .swiper-button-prev {
            color: white !important;
            background: rgba(0,0,0,0.5);
            border-radius: 50%;
            width: 50px !important;
            height: 50px !important;
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 20px !important;
        }

        .swiper-pagination-bullet {
            background: white !important;
            opacity: 0.7;
        }

        .swiper-pagination-bullet-active {
            opacity: 1;
            background: var(--secondary-color) !important;
        }
    </style>
    
    <!-- Updated Logo Sizes -->
    <style>
        /* Desktop Logo - ENHANCED SIZE AND STYLING */
        .logo-image {
            height: 110px !important;
            width: auto;
            object-fit: contain;
            transition: all 0.3s ease;
            display: block;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        /* Mobile Logo - ENHANCED SIZE AND STYLING */
        .mobile-logo {
            height: 75px !important;
            width: auto;
            object-fit: contain;
            display: block;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }
    </style>
</head>

<body class="bg-background universal-smooth-scroll ios-optimized">
    @if(!request()->routeIs('account'))
        @include('layouts.header')
    @endif

    <main>
        @yield('content')
    </main>

    @if(!request()->routeIs('account'))
        @include('layouts.footer')
    @endif

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/counterup2@2.0.4/dist/index.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
        });

        // Show SweetAlert for login success
        @if(session('login_success'))
            Swal.fire({
                icon: 'success',
                title: 'Login Successful!',
                text: '{{ session("login_success") }}',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'top-end',
                toast: true,
                background: '#1a3a1a',
                color: '#ffffff',
                iconColor: '#dbb363'
            });
        @endif

        // Show SweetAlert for other success messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session("success") }}',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'top-end',
                toast: true,
                background: '#1a3a1a',
                color: '#ffffff',
                iconColor: '#dbb363'
            });
        @endif
    </script>

    @stack('scripts')
</body>
</html>
