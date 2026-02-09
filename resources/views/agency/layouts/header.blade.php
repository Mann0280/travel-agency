@php
    $agencyUser = Auth::guard('agency')->user();
    if (!$agencyUser && Auth::guard('admin')->check() && session()->has('impersonate_agency_id')) {
        $agencyUser = \App\Models\Agency::find(session('impersonate_agency_id'));
    }
    $userName = $agencyUser->name ?? 'Partner';
@endphp

<header class="bg-white/80 backdrop-blur-xl border-b border-gray-200/50 sticky top-0 z-40 shadow-sm">
    <div class="flex justify-between items-center px-4 md:px-8 py-4 md:py-5">
        <div class="flex items-center gap-4">
            <button id="mobileMenuBtn" onclick="toggleSidebar()" class="lg:hidden bg-gradient-to-r from-forest to-forest-dark text-white p-3 rounded-xl">
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <div class="hidden md:block">
                <span class="text-xs font-bold text-forest uppercase tracking-widest font-poppins">@yield('page_title', 'Partner Dashboard')</span>
            </div>

            @if(Auth::guard('admin')->check() && session()->has('impersonate_agency_id'))
                <form action="{{ route('admin.agencies.stop-impersonate') }}" method="POST" class="ml-4">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-red-600 transition-all shadow-md">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Leave Impersonation
                    </button>
                </form>
            @endif
        </div>
        
        <div class="flex items-center space-x-4">
            <div class="flex flex-col items-end hidden md:flex">
                <span class="text-sm font-bold text-gray-900 font-poppins">{{ $userName }}</span>
                <span class="text-[10px] text-forest font-bold uppercase tracking-wider font-poppins">Travel Partner</span>
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-gradient-to-br from-forest to-forest-dark flex items-center justify-center text-gold font-black shadow-lg">
                {{ substr($userName, 0, 1) }}
            </div>
        </div>
    </div>
</header>
