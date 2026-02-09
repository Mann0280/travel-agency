@php
    $user = Auth::guard('admin')->user();
    $pendingBookingsCount = \App\Models\Booking::where('status', 'pending')->count();
    $recentPendingBookings = \App\Models\Booking::with('user')->where('status', 'pending')->latest()->take(5)->get();
@endphp

<!-- Mobile Header (Fixed) -->
<div class="lg:hidden mobile-header p-3 flex items-center justify-between">
    <div class="flex items-center">
        <button @click="sidebarOpen = true" class="text-white p-2">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <h1 class="text-lg font-bold text-white ml-2">ZUBEEE Admin</h1>
    </div>
    <div class="flex items-center space-x-2">
        <!-- Mobile Notifications -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="text-white relative p-2">
                <i class="fas fa-bell text-lg"></i>
                @if($pendingBookingsCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] rounded-full h-5 w-5 flex items-center justify-center border-2 border-[#17320b]">
                        {{ $pendingBookingsCount }}
                    </span>
                @endif
            </button>
            <!-- Mobile Notif Dropdown -->
            <div x-show="open" @click.away="open = false" 
                 class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg z-50 overflow-hidden">
                <div class="p-3 border-b bg-gray-50">
                    <h3 class="font-bold text-sm">Notifications</h3>
                </div>
                <div class="max-h-60 overflow-y-auto">
                    @forelse($recentPendingBookings as $booking)
                        <a href="{{ route('admin.bookings.index') }}" class="block p-3 border-b hover:bg-gray-50 text-xs">
                            <p class="font-semibold">New booking request</p>
                            <p class="text-gray-500">{{ $booking->created_at->diffForHumans() }}</p>
                        </a>
                    @empty
                        <div class="p-4 text-center text-gray-400 text-xs">No notifications</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Mobile Admin Menu -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="text-white p-2">
                <div class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center border border-white/20">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
            </button>
            <div x-show="open" @click.away="open = false"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50 overflow-hidden">
                <div class="p-3 border-b bg-gray-50">
                    <p class="font-semibold text-sm truncate">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                </div>
                <a href="{{ route('home') }}" target="_blank" class="block px-4 py-2 hover:bg-gray-100 text-sm">
                    <i class="fas fa-external-link-alt mr-2 text-xs"></i>View Site
                </a>
                <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 hover:bg-gray-100 text-sm">
                    <i class="fas fa-cog mr-2 text-xs"></i>Settings
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
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
            <h2 class="text-2xl font-bold text-[#17320b]">@yield('page_title', 'Dashboard')</h2>
            <p class="text-gray-600 text-sm">Welcome back, {{ $user->name }}</p>
        </div>
        <div class="flex items-center space-x-6">
            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="text-gray-600 hover:text-[#17320b] relative transition-colors">
                    <i class="fas fa-bell text-xl"></i>
                    @if($pendingBookingsCount > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] rounded-full h-5 w-5 flex items-center justify-center border-2 border-white font-bold">
                            {{ $pendingBookingsCount }}
                        </span>
                    @endif
                </button>
                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-4 w-80 bg-white rounded-xl shadow-xl z-50 overflow-hidden border border-gray-100">
                    <div class="p-4 border-b bg-gray-50">
                        <h3 class="font-bold text-[#17320b]">Notifications</h3>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        @forelse($recentPendingBookings as $booking)
                            <a href="{{ route('admin.bookings.index') }}" class="block p-4 border-b hover:bg-gray-50 transition-colors">
                                <p class="font-semibold text-sm text-gray-800">New booking received</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $booking->created_at->diffForHumans() }}</p>
                            </a>
                        @empty
                            <div class="p-8 text-center text-gray-400">
                                <i class="fas fa-bell-slash text-2xl mb-2 opacity-50"></i>
                                <p class="text-sm font-medium">No pending notifications</p>
                            </div>
                        @endforelse
                    </div>
                    @if($pendingBookingsCount > 0)
                        <div class="p-3 border-t text-center">
                            <a href="{{ route('admin.bookings.index') }}" class="text-[#a8894d] text-sm font-bold hover:underline">
                                View all bookings
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Admin Menu (Desktop) -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex items-center space-x-3 text-gray-700 hover:text-[#17320b] transition-all group">
                    <div class="flex flex-col items-end mr-1">
                        <span class="text-sm font-bold text-gray-900 group-hover:text-[#17320b]">{{ $user->name }}</span>
                        <span class="text-[10px] font-bold text-[#a8894d] uppercase tracking-wider">Administrator</span>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#17320b] to-[#1a3a0f] flex items-center justify-center shadow-lg group-hover:shadow-md transition-all">
                        <i class="fas fa-user-shield text-[#a8894d] text-base"></i>
                    </div>
                    <i class="fas fa-chevron-down text-xs text-gray-400 group-hover:text-[#17320b]" :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-4 w-56 bg-white rounded-xl shadow-xl z-50 overflow-hidden border border-gray-100">
                    <div class="p-4 border-b bg-gray-50">
                        <p class="font-bold text-sm text-gray-800">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                    </div>
                    <div class="p-2">
                        <a href="{{ route('home') }}" target="_blank" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg hover:bg-gray-50 text-sm text-gray-700 transition-colors">
                            <i class="fas fa-external-link-alt text-gray-400 w-4"></i>
                            <span class="font-medium">View Site</span>
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg hover:bg-gray-50 text-sm text-gray-700 transition-colors">
                            <i class="fas fa-cog text-gray-400 w-4"></i>
                            <span class="font-medium">Settings</span>
                        </a>
                        <div class="my-1 border-t border-gray-100"></div>
                        <form action="{{ route('admin.logout') }}" method="POST">
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
