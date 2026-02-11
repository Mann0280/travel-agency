@php
    $agency = null;
    if (Auth::guard('agency')->check()) {
        $agency = Auth::guard('agency')->user();
    } elseif (Auth::guard('admin')->check() && session()->has('impersonate_agency_id')) {
        $agency = \App\Models\Agency::find(session('impersonate_agency_id'));
    }
@endphp
<aside id="agencySidebar" 
       class="sidebar overflow-y-auto custom-scrollbar bg-forest" 
       :class="{ 'open': sidebarOpen }">
    <!-- Logo -->
    <div class="p-4 border-b border-white/10">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                @if($agency && $agency->logo)
                    <img src="{{ asset('storage/' . $agency->logo) }}" alt="Logo" class="h-10 w-10 mr-3 object-cover rounded-lg border border-gold/30">
                @else
                    <div class="w-10 h-10 bg-white/10 flex items-center justify-center mr-3 rounded-lg border border-gold/30">
                        <i class="fas fa-briefcase text-gold"></i>
                    </div>
                @endif
                <div>
                     <h1 class="text-xl font-bold text-white tracking-tight">{{ $site_settings->get('site_name', 'ZUBEEE') }}</h1>
                     <span class="text-[10px] font-bold text-gold uppercase tracking-wider block">Agency Panel</span>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-white p-2">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Agency Profile Summary -->
    <div class="p-4 border-b border-white/10">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center mr-3 text-gold">
                {{ substr($agency->name ?? 'A', 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="font-semibold text-white truncate text-sm">{{ $agency->name ?? 'Agency' }}</p>
                <p class="text-xs text-white/70 truncate">{{ $agency->email ?? '' }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="p-2">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('agency.dashboard') }}"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white {{ request()->routeIs('agency.dashboard') ? 'active-menu' : '' }}">
                    <i class="fas fa-th-large w-6 mr-3"></i>
                    <span class="text-sm">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('agency.packages.index') }}"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white {{ request()->routeIs('agency.packages.*') ? 'active-menu' : '' }}">
                    <i class="fas fa-suitcase-rolling w-6 mr-3"></i>
                    <span class="text-sm">My Packages</span>
                </a>
            </li>
            
            {{-- Bookings hidden as per previous request --}}
            {{-- <li>
                <a href="{{ route('agency.bookings') }}"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white {{ request()->routeIs('agency.bookings*') ? 'active-menu' : '' }}">
                    <i class="fas fa-calendar-check w-6 mr-3"></i>
                    <span class="text-sm">Bookings</span>
                </a>
            </li> --}}

            <li>
                <a href="{{ route('agency.analytics.clicks') }}"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white {{ request()->routeIs('agency.analytics.*') ? 'active-menu' : '' }}">
                    <i class="fas fa-chart-line w-6 mr-3"></i>
                    <span class="text-sm">Click Analytics</span>
                </a>
            </li>
            <li>
                <a href="{{ route('agency.profile.edit') }}"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white {{ request()->routeIs('agency.profile.*') ? 'active-menu' : '' }}">
                    <i class="fas fa-building w-6 mr-3"></i>
                    <span class="text-sm">Agency Profile</span>
                </a>
            </li>
            <li>
                <a href="{{ route('home') }}" target="_blank"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white">
                    <i class="fas fa-external-link-alt w-6 mr-3"></i>
                    <span class="text-sm">View Website</span>
                </a>
            </li>
            <li>
                <form action="{{ route('agency.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center w-full p-3 rounded-lg hover:bg-red-500/10 text-red-400 text-left transition-colors">
                        <i class="fas fa-sign-out-alt w-6 mr-3"></i>
                        <span class="text-sm font-semibold">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarNav = document.querySelector('#agencySidebar nav');
        if (sidebarNav) {
            // Restore scroll position
            const scrollPos = localStorage.getItem('agencySidebarScrollPos');
            if (scrollPos) {
                sidebarNav.scrollTop = parseInt(scrollPos, 10);
            }

            // Save scroll position
            sidebarNav.addEventListener('scroll', function() {
                localStorage.setItem('agencySidebarScrollPos', sidebarNav.scrollTop);
            });
        }
    });
</script>
@endpush
