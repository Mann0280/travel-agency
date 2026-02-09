<aside id="adminSidebar" 
       class="sidebar overflow-y-auto custom-scrollbar" 
       :class="{ 'open': sidebarOpen }">
    <!-- Logo -->
    <div class="p-4 border-b border-secondary/30">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                @if($site_settings->get('site_logo'))
                    <img src="{{ asset($site_settings->get('site_logo')) }}" alt="Logo" class="h-10 w-10 mr-3 object-contain">
                @else
                    <div class="w-10 h-10 bg-secondary flex items-center justify-center mr-3 rounded-lg">
                        <i class="fas fa-plane text-white"></i>
                    </div>
                @endif
                <h1 class="text-xl font-bold text-white">ZUBEEE Admin</h1>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-white p-2">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
    </div>

    <!-- User Profile -->
    <div class="p-4 border-b border-secondary/30">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center mr-3">
                <i class="fas fa-user text-white"></i>
            </div>
            <div>
                <p class="font-semibold text-white truncate max-w-[150px]">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-gray-300">Administrator</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="p-2">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white {{ request()->routeIs('admin.dashboard') ? 'active-menu' : '' }}">
                    <i class="fas fa-tachometer-alt w-6 mr-3"></i>
                    <span class="text-sm">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.banner.index') }}"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white {{ request()->routeIs('admin.banner.*') ? 'active-menu' : '' }}">
                    <i class="fas fa-images w-6 mr-3"></i>
                    <span class="text-sm">Manage Banners</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.popular-searches.index') }}"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white {{ request()->routeIs('admin.popular-searches.*') ? 'active-menu' : '' }}">
                    <i class="fas fa-search w-6 mr-3"></i>
                    <span class="text-sm">Popular Searches</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.packages.index') }}"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white {{ request()->routeIs('admin.packages.*') ? 'active-menu' : '' }}">
                    <i class="fas fa-suitcase w-6 mr-3"></i>
                    <span class="text-sm">Packages</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.agencies.index') }}"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white {{ request()->routeIs('admin.agencies.*') ? 'active-menu' : '' }}">
                    <i class="fas fa-building w-6 mr-3"></i>
                    <span class="text-sm">Agencies</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white {{ request()->routeIs('admin.users.*') ? 'active-menu' : '' }}">
                    <i class="fas fa-users w-6 mr-3"></i>
                    <span class="text-sm">Users</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.bookings.index') }}"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white {{ request()->routeIs('admin.bookings.*') ? 'active-menu' : '' }}">
                    <i class="fas fa-calendar-check w-6 mr-3"></i>
                    <span class="text-sm">Bookings</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.stories.index') }}"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white {{ request()->routeIs('admin.stories.*') ? 'active-menu' : '' }}">
                    <i class="fas fa-book w-6 mr-3"></i>
                    <span class="text-sm">Manage Stories</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.account-content.index') }}"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white {{ request()->routeIs('admin.account-content.*') ? 'active-menu' : '' }}">
                    <i class="fas fa-user-circle w-6 mr-3"></i>
                    <span class="text-sm">My Account Content</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reviews.index') }}"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white {{ request()->routeIs('admin.reviews.*') ? 'active-menu' : '' }}">
                    <i class="fas fa-star w-6 mr-3"></i>
                    <span class="text-sm">User Reviews</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.feedback.index') }}"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white {{ request()->routeIs('admin.feedback.*') ? 'active-menu' : '' }}">
                    <i class="fas fa-comment-dots w-6 mr-3"></i>
                    <span class="text-sm">Customer Feedback</span>
                </a>
            </li>
            <li>
                <a href="{{ route('home') }}" target="_blank"
                    class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white">
                    <i class="fas fa-external-link-alt w-6 mr-3"></i>
                    <span class="text-sm">View Site</span>
                </a>
            </li>
            <li>
                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
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
        const sidebarNav = document.querySelector('#adminSidebar nav');
        if (sidebarNav) {
            // Restore scroll position
            const scrollPos = localStorage.getItem('sidebarScrollPos');
            if (scrollPos) {
                sidebarNav.scrollTop = parseInt(scrollPos, 10);
            }

            // Save scroll position
            sidebarNav.addEventListener('scroll', function() {
                localStorage.setItem('sidebarScrollPos', sidebarNav.scrollTop);
            });
        }
    });

    // Support for Turbo/Turbolinks if enabled
    document.addEventListener('turbo:load', function() {
        const sidebarNav = document.querySelector('#adminSidebar nav');
        if (sidebarNav) {
            const scrollPos = localStorage.getItem('sidebarScrollPos');
            if (scrollPos) {
                sidebarNav.scrollTop = parseInt(scrollPos, 10);
            }
            
            sidebarNav.addEventListener('scroll', function() {
                localStorage.setItem('sidebarScrollPos', sidebarNav.scrollTop);
            });
        }
    });
</script>
@endpush
