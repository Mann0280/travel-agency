<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', \App\Models\Setting::get('site_name', 'ZUBEEE Travel Admin'))</title>
    
    @php
        $favicon = \App\Models\Setting::get('site_favicon') ?: \App\Models\Setting::get('site_logo');
    @endphp
    @if($favicon)
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset($favicon) }}">
        <link rel="shortcut icon" href="{{ asset($favicon) }}">
    @endif
    
    <!-- Google Fonts: Poppins & Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Dynamic Colors -->
    @php
        $primaryColor = \App\Models\Setting::get('primary_color', '#17320b');
        $secondaryColor = \App\Models\Setting::get('secondary_color', '#a8894d');
    @endphp

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'forest': '{{ $primaryColor }}',
                        'forest-light': '{{ $primaryColor }}DD', // Approximate light
                        'forest-dark': '{{ $primaryColor }}', // Should be darker ideally, using same for now or simple opacity
                        'gold': '{{ $secondaryColor }}',
                        'champagne': '#fdfbf7',
                        'slate': '#57688a',
                        'admin-black': '#0f172a',
                        'admin-gray': '#1e293b',
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                        'inter': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        :root {
            --primary-color: {{ $primaryColor }};
            --secondary-color: {{ $secondaryColor }};
        }

        /* Fixed layout structure */
        html,
        body {
            height: 100%;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }

        .admin-layout {
            display: flex;
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        /* Fixed sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 250px;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-color) 100%); /* simplified gradient using primary */
            z-index: 50;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        /* Main content wrapper */
        .content-wrapper {
            flex: 1;
            margin-left: 250px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
        }

        /* Fixed desktop header */
        .desktop-header-fixed {
            position: sticky;
            top: 0;
            left: 250px;
            right: 0;
            z-index: 40;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Scrollable content area */
        .content-area {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 20px;
            -webkit-overflow-scrolling: touch;
        }

        /* Mobile specific styles */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                z-index: 60;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .content-wrapper {
                margin-left: 0;
                width: 100%;
            }

            .mobile-sidebar-open .content-wrapper {
                filter: brightness(0.7);
                pointer-events: none;
            }

            /* Fixed mobile header */
            .mobile-header {
                position: sticky;
                top: 0;
                left: 0;
                right: 0;
                z-index: 50;
                height: 60px;
                background: var(--primary-color);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
        }

        /* Active menu styling */
        .active-menu {
            background-color: rgba(255, 255, 255, 0.1); /* Neutral active state for flexibility */
            border-left: 3px solid var(--secondary-color);
            color: white;
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 3px;
            opacity: 0.5;
        }

        /* Table responsive styles */
        .data-table-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Forms & Buttons Utility Classes */
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151; /* gray-700 */
            margin-bottom: 0.5rem;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db; /* gray-300 */
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            color: #1f2937; /* gray-800 */
            background-color: #fff;
            transition: all 0.2s;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(168, 137, 77, 0.2); /* Gold ring */
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            color: white;
            border-color: var(--secondary-color);
        }

        .btn-primary:hover {
            background-color: #9d7c4f;
            border-color: #9d7c4f;
        }

        .btn-secondary {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .btn-secondary:hover {
            background-color: #0f2407;
            border-color: #0f2407;
        }

        .btn-outline {
            background-color: transparent;
            color: #4b5563; /* gray-600 */
            border-color: #d1d5db; /* gray-300 */
        }

        .btn-outline:hover {
            background-color: #f3f4f6; /* gray-50 */
            color: #1f2937; /* gray-800 */
        }

        .btn-danger {
            background-color: #ef4444; /* red-500 */
            color: white;
            border-color: #ef4444;
        }

        .btn-danger:hover {
            background-color: #dc2626; /* red-600 */
            border-color: #dc2626;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .card {
            background: white;
            border-radius: 0.75rem; /* Slightly more rounded */
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #f3f4f6; /* gray-100 */
        }

        /* Text Colors Utility */
        .text-forest { color: var(--primary-color) !important; }
        .text-gold { color: var(--secondary-color) !important; }
        .bg-forest { background-color: var(--primary-color) !important; }
        .bg-gold { background-color: var(--secondary-color) !important; }
        
    </style>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="admin-layout" x-data="{ sidebarOpen: false }" :class="{ 'mobile-sidebar-open': sidebarOpen }">
        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/50 z-[55] lg:hidden"
             x-transition:enter="transition opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>
        
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Header -->
            @include('admin.layouts.header')
            
            <!-- Scrollable Content Area -->
            <div class="content-area">
                <main class="p-4 md:p-8">
                    <!-- Session Messages -->
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm flex items-center justify-between animate-fade-in-down">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600 mr-3">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm flex items-center justify-between animate-fade-in-down">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-red-600 mr-3">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
                                </div>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }
        
        // Global AJAX setup for CSRF
        document.addEventListener('DOMContentLoaded', () => {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            if (token) {
                // For legacy jQuery if used
                if (window.jQuery) {
                    window.jQuery.ajaxSetup({
                        headers: { 'X-CSRF-TOKEN': token }
                    });
                }
                // For typical fetch usage
                const originalFetch = window.fetch;
                window.fetch = function() {
                    let [resource, config] = arguments;
                    if (config && ['POST', 'PUT', 'DELETE', 'PATCH'].includes(config.method?.toUpperCase())) {
                        config.headers = {
                            ...config.headers,
                            'X-CSRF-TOKEN': token
                        };
                    }
                    return originalFetch(resource, config);
                };
            }
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('adminSidebar');
            const menuBtn = document.getElementById('mobileMenuBtn');
            
            if (window.innerWidth < 1024) {
                if (!sidebar.contains(event.target) && !menuBtn.contains(event.target) && sidebar.classList.contains('open')) {
                    toggleSidebar();
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
