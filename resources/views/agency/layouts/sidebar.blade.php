@php
    $agency = null;
    if (Auth::guard('agency')->check()) {
        $agency = Auth::guard('agency')->user();
    } elseif (Auth::guard('admin')->check() && session()->has('impersonate_agency_id')) {
        $agency = \App\Models\Agency::find(session('impersonate_agency_id'));
    }
@endphp
<aside id="agencySidebar" class="sidebar fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-forest to-forest-dark text-white z-50 shadow-2xl">
    <div class="flex flex-col h-full">
        <!-- Brand Identity -->
        <div class="p-6 border-b border-white/10 bg-forest-dark/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative flex-shrink-0">
                        @if($agency && $agency->logo)
                            <img src="{{ asset('storage/' . $agency->logo) }}" alt="{{ $agency->name }}" class="h-10 w-10 object-cover rounded-xl border border-gold/30">
                        @else
                            <div class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-gold/30">
                                <i class="fas fa-briefcase text-gold text-lg"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-sm font-black text-white leading-none font-poppins tracking-tight uppercase">{{ $agency->name ?? 'ZUBEEE' }}</h1>
                        <span class="text-[10px] font-bold text-gold font-poppins uppercase tracking-wider">Partner Panel</span>
                    </div>
                </div>
                
                <button onclick="toggleSidebar()" class="lg:hidden text-white hover:text-gold transition-colors p-1.5 hover:bg-white/10 rounded-lg">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
            <div class="mb-6 px-2">
                <a href="{{ route('home') }}" target="_blank" 
                   class="flex items-center justify-between w-full px-5 py-4 bg-white/10 backdrop-blur-sm rounded-2xl text-white shadow-lg hover:bg-white/20 transition-all duration-300 group border border-white/20">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-globe text-gold text-lg"></i>
                        <span class="text-sm font-bold font-poppins">View Website</span>
                    </div>
                    <i class="fas fa-arrow-right text-gold text-sm group-hover:translate-x-2 transition-all duration-300"></i>
                </a>
            </div>

            <!-- Dashboard -->
            <a href="{{ route('agency.dashboard') }}" class="group flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 {{ request()->routeIs('agency.dashboard') ? 'bg-white/20 text-white shadow-lg border border-gold/30' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <div class="w-10 h-10 rounded-lg {{ request()->routeIs('agency.dashboard') ? 'bg-gold/30' : 'bg-white/5 group-hover:bg-white/10' }} flex items-center justify-center mr-3">
                    <i class="fas fa-th-large"></i>
                </div>
                <span class="text-sm font-semibold font-poppins">Dashboard</span>
            </a>

            <!-- My Packages -->
            <a href="{{ route('agency.packages.index') }}" class="group flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 {{ request()->routeIs('agency.packages.*') ? 'bg-white/20 text-white shadow-lg border border-gold/30' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <div class="w-10 h-10 rounded-lg {{ request()->routeIs('agency.packages.*') ? 'bg-gold/30' : 'bg-white/5 group-hover:bg-white/10' }} flex items-center justify-center mr-3">
                    <i class="fas fa-suitcase-rolling"></i>
                </div>
                <span class="text-sm font-semibold font-poppins">My Packages</span>
            </a>

            <!-- Bookings -->
            <a href="{{ route('agency.bookings') }}" class="group flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 {{ request()->routeIs('agency.bookings*') ? 'bg-white/20 text-white shadow-lg border border-gold/30' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <div class="w-10 h-10 rounded-lg {{ request()->routeIs('agency.bookings*') ? 'bg-gold/30' : 'bg-white/5 group-hover:bg-white/10' }} flex items-center justify-center mr-3">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <span class="text-sm font-semibold font-poppins">Bookings</span>
            </a>

            <!-- Profile -->
            <a href="{{ route('agency.profile.edit') }}" class="group flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 {{ request()->routeIs('agency.profile.*') ? 'bg-white/20 text-white shadow-lg border border-gold/30' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <div class="w-10 h-10 rounded-lg {{ request()->routeIs('agency.profile.*') ? 'bg-gold/30' : 'bg-white/5 group-hover:bg-white/10' }} flex items-center justify-center mr-3">
                    <i class="fas fa-building"></i>
                </div>
                <span class="text-sm font-semibold font-poppins">Agency Profile</span>
            </a>

        </nav>

        <!-- Logout -->
        <div class="p-5 border-t border-white/10 bg-forest-dark/30">
            <form action="{{ route('agency.logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-3 w-full px-5 py-3.5 bg-red-500/20 hover:bg-red-500 text-white rounded-xl transition-all duration-300 group border border-red-500/30 hover:border-red-500">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="text-sm font-bold font-poppins">Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>
