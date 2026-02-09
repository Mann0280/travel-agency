<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ZUBEEE Agency Panel')</title>
    
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
        :root {
            --forest: #1a3a1a;
            --forest-light: #2a4a2a;
            --forest-dark: #0a2a0a;
            --gold: #dbb363;
            --champagne: #fdfbf7;
            --slate: #57688a;
            --admin-black: #0f172a;
            --admin-gray: #1e293b;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--champagne) 0%, #ffffff 100%);
        }
        
        /* Responsive Sidebar */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
            }
        }
        
        /* Luxury Gradient Backgrounds */
        .gradient-forest {
            background: linear-gradient(135deg, var(--forest) 0%, var(--forest-dark) 100%);
        }
        
        .gradient-gold {
            background: linear-gradient(135deg, var(--gold) 0%, #c9a353 100%);
        }
        
        /* Premium Shadow Effects */
        .shadow-luxury {
            box-shadow: 0 20px 60px -15px rgba(58, 109, 44, 0.3);
        }
        
        .shadow-soft {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        /* Smooth Animations */
        @keyframes fadeInUp {
            from { 
                opacity: 0; 
                transform: translateY(30px);
            }
            to { 
                opacity: 1; 
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        
        /* Card Styles - Travel Themed */
        .card {
            background: white;
            border-radius: 20px;
            border: 1px solid rgba(58, 109, 44, 0.1);
            padding: 1.5rem;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }
        
        @media (min-width: 768px) {
            .card {
                padding: 2rem;
            }
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--forest) 0%, var(--gold) 100%);
            transform: scaleX(0);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .card:hover::before {
            transform: scaleX(1);
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(58, 109, 44, 0.15);
        }
        
        /* Premium Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, var(--forest) 0%, var(--forest-dark) 100%);
            color: white;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 600;
            padding: 12px 28px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 8px 20px rgba(58, 109, 44, 0.3);
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(58, 109, 44, 0.4);
        }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(58, 109, 44, 0.05);
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--forest-light), var(--forest));
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: var(--forest-dark);
        }
        
        /* Mobile Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 40;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        
        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }
    </style>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine JS -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-champagne via-white to-champagne">
    <!-- Mobile Overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>
    
    <!-- Sidebar -->
    @include('agency.layouts.sidebar')
    
    <!-- Main Content -->
    <div class="main-content lg:ml-64 min-h-screen flex flex-col">
        <!-- Header -->
        @include('agency.layouts.header')
        
        <!-- Content -->
        <main class="p-4 md:p-8 flex-1">
            <div class="animate-fade-in-up">
                @if(session('success'))
                    <div class="bg-forest/10 border-l-4 border-forest p-4 mb-8">
                        <p class="text-forest font-bold">{{ session('success') }}</p>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-500/10 border-l-4 border-red-500 p-4 mb-8">
                        <p class="text-red-600 font-bold">{{ session('error') }}</p>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('agencySidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('agencySidebar');
            const menuBtn = document.getElementById('mobileMenuBtn');
            
            if (window.innerWidth < 1024) {
                if (sidebar && !sidebar.contains(event.target) && menuBtn && !menuBtn.contains(event.target) && sidebar.classList.contains('open')) {
                    toggleSidebar();
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
