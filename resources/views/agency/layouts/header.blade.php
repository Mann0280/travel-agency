@php
    $agencyUser = Auth::guard('agency')->user();
    if (!$agencyUser && Auth::guard('admin')->check() && session()->has('impersonate_agency_id')) {
        $agencyUser = \App\Models\Agency::find(session('impersonate_agency_id'));
    }
    $userName = $agencyUser->name ?? 'Partner';
    $userEmail = $agencyUser->email ?? '';
@endphp

<!-- Mobile Header (Fixed) -->
<div class="lg:hidden mobile-header p-3 flex items-center justify-between">
    <div class="flex items-center">
        <button @click="sidebarOpen = true" class="text-white p-2">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <h1 class="text-lg font-bold text-white ml-2">{{ $site_settings->get('site_name', 'ZUBEEE') }} Agency</h1>
    </div>
    <div class="flex items-center space-x-2">
        <!-- Mobile User Menu -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="text-white p-2">
                <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center border border-white/20 text-gold">
                    <i class="fas fa-user text-sm"></i>
                </div>
            </button>
            <div x-show="open" @click.away="open = false"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50 overflow-hidden">
                <div class="p-3 border-b bg-gray-50">
                    <p class="font-semibold text-sm truncate text-forest">{{ $userName }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $userEmail }}</p>
                </div>
                <a href="{{ route('home') }}" target="_blank" class="block px-4 py-2 hover:bg-gray-100 text-sm">
                    <i class="fas fa-external-link-alt mr-2 text-xs"></i>View Site
                </a>
                <a href="{{ route('agency.profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100 text-sm">
                    <i class="fas fa-building mr-2 text-xs"></i>Profile
                </a>
                <form action="{{ route('agency.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-red-50 text-red-600 text-sm">
                        <i class="fas fa-sign-out-alt mr-2 text-xs"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Desktop Header (Fixed) -->
<div class="desktop-header-fixed hidden lg:block p-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-forest">@yield('page_title', 'Dashboard')</h2>
            <div class="flex items-center gap-4">
                <p class="text-gray-600 text-sm">Welcome back, {{ $userName }}</p>
                @if(Auth::guard('admin')->check() && session()->has('impersonate_agency_id'))
                    <form action="{{ route('admin.agencies.stop-impersonate') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-red-100 text-red-600 text-[10px] font-bold uppercase tracking-wider rounded border border-red-200 hover:bg-red-200 transition-all">
                            <i class="fas fa-sign-out-alt mr-1"></i> End Impersonation
                        </button>
                    </form>
                @endif
            </div>
        </div>
        <div class="flex items-center space-x-6">
            <!-- Notifications (Placeholder) -->
             <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="text-gray-600 hover:text-forest relative transition-colors">
                    <i class="fas fa-bell text-xl"></i>
                    {{-- Notification count can go here --}}
                </button>
                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-4 w-80 bg-white rounded-xl shadow-xl z-50 overflow-hidden border border-gray-100">
                    <div class="p-4 border-b bg-gray-50">
                        <h3 class="font-bold text-forest">Notifications</h3>
                    </div>
                    <div class="p-8 text-center text-gray-400">
                        <i class="fas fa-bell-slash text-2xl mb-2 opacity-50"></i>
                         <p class="text-sm font-medium">No new notifications</p>
                    </div>
                </div>
            </div>

            <!-- Agency Menu (Desktop) -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex items-center space-x-3 text-gray-700 hover:text-forest transition-all group">
                    <div class="flex flex-col items-end mr-1">
                        <span class="text-sm font-bold text-gray-900 group-hover:text-forest">{{ $userName }}</span>
                        <span class="text-[10px] font-bold text-gold uppercase tracking-wider">Agency Partner</span>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-forest flex items-center justify-center shadow-lg group-hover:shadow-md transition-all border border-gold/30">
                        <span class="text-gold font-bold">{{ substr($userName, 0, 1) }}</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs text-gray-400 group-hover:text-forest" :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-4 w-56 bg-white rounded-xl shadow-xl z-50 overflow-hidden border border-gray-100">
                    <div class="p-4 border-b bg-gray-50">
                        <p class="font-bold text-sm text-gray-800">{{ $userName }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $userEmail }}</p>
                    </div>
                    <div class="p-2">
                        <a href="{{ route('home') }}" target="_blank" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg hover:bg-gray-50 text-sm text-gray-700 transition-colors">
                            <i class="fas fa-external-link-alt text-gray-400 w-4"></i>
                            <span class="font-medium">View Site</span>
                        </a>
                        <a href="{{ route('agency.profile.edit') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg hover:bg-gray-50 text-sm text-gray-700 transition-colors">
                            <i class="fas fa-building text-gray-400 w-4"></i>
                            <span class="font-medium">Profile</span>
                        </a>
                        <div class="my-1 border-t border-gray-100"></div>
                        <form action="{{ route('agency.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center space-x-3 w-full px-3 py-2.5 rounded-lg hover:bg-red-50 text-sm text-red-600 transition-colors group">
                                <i class="fas fa-sign-out-alt text-red-400 w-4 transition-transform group-hover:translate-x-1"></i>
                                <span class="font-bold">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
