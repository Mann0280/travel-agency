<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ZUBEEE Travel Admin')</title>
    
    <!-- Google Fonts: Poppins & Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'forest': '#1a3a1a',
                        'forest-light': '#2a4a2a',
                        'forest-dark': '#0a2a0a',
                        'gold': '#dbb363',
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
            background: linear-gradient(180deg, #17320b 0%, #1a3a0f 100%);
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
                background: #17320b;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
        }

        /* Active menu styling */
        .active-menu {
            background-color: rgba(168, 137, 77, 0.2);
            border-left: 3px solid #a8894d;
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
            background: rgba(168, 137, 77, 0.5);
            border-radius: 3px;
        }

        /* Table responsive styles */
        .data-table-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Forms & Buttons from snippet */
        .btn-primary {
            background-color: #a8894d;
            color: white;
        }

        .btn-primary:hover {
            background-color: #9d7c4f;
        }
    </style>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body class="bg-admin-content">
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
