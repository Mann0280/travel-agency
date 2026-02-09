@extends('admin.layouts.app')

@section('title', 'Booking Manifest - #BK-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center text-sm text-gray-400 mb-2 font-medium">
                <a href="{{ route('admin.bookings.index') }}" class="hover:text-forest transition-colors text-xs font-bold uppercase tracking-widest">Voyage Pipeline</a>
                <i class="fas fa-chevron-right mx-2 text-[10px]"></i>
                <span class="text-gray-600 text-xs font-bold uppercase tracking-widest">Manifest #BK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <h1 class="text-3xl font-black text-forest font-poppins italic">Booking Manifest</h1>
            <p class="text-gray-500 text-sm font-medium">Detailed reservation dossier and financial settlement</p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="px-6 py-2 border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-all">
                <i class="fas fa-print mr-2"></i> Print Dossier
            </button>
            <a href="{{ route('admin.bookings.index') }}" class="px-6 py-2 bg-forest text-white font-bold rounded-xl hover:bg-forest-dark transition-all shadow-lg hover:shadow-forest/20">
                <i class="fas fa-arrow-left mr-2"></i> Back to Pipeline
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Main Dossier -->
        <div class="md:col-span-2 space-y-8">
            <!-- Customer Dossier -->
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-luxury overflow-hidden">
                <div class="px-10 py-6 bg-forest italic flex justify-between items-center">
                    <h3 class="text-lg font-black text-white/90 font-poppins uppercase tracking-widest">Customer Profile</h3>
                    <span class="px-3 py-1 bg-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-full">Source: {{ $booking->booking_source }}</span>
                </div>
                <div class="p-10">
                    <div class="flex items-center gap-8 mb-10">
                        <div class="w-24 h-24 rounded-[2rem] bg-gradient-to-br from-forest to-forest-dark flex items-center justify-center text-white font-black text-4xl shadow-xl">
                            {{ substr($booking->user->name ?? 'G', 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-3xl font-black text-forest font-poppins">{{ $booking->user->name ?? 'Guest Explorer' }}</h2>
                            <div class="flex flex-wrap gap-4 mt-3">
                                <span class="flex items-center gap-2 text-slate font-bold text-sm">
                                    <i class="fas fa-envelope text-gold"></i> {{ $booking->user->email ?? 'N/A' }}
                                </span>
                                <span class="flex items-center gap-2 text-slate font-bold text-sm">
                                    <i class="fas fa-phone text-gold"></i> {{ $booking->user->phone ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-10 pt-10 border-t border-gray-50">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Registration ID</p>
                            <p class="text-lg font-black text-forest font-poppins italic">USER-{{ str_pad($booking->user->id ?? 0, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Member Since</p>
                            <p class="text-lg font-black text-forest font-poppins italic">{{ $booking->user ? $booking->user->created_at->format('M Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Itinerary Core -->
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-luxury overflow-hidden">
                <div class="px-10 py-6 bg-gold italic flex justify-between items-center">
                    <h3 class="text-lg font-black text-forest font-poppins uppercase tracking-widest">Voyage Details</h3>
                    <div class="flex items-center gap-2">
                         @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-[10px] {{ $i <= ($booking->package->rating ?? 0) ? 'text-forest' : 'text-forest/20' }}"></i>
                        @endfor
                    </div>
                </div>
                <div class="p-10">
                    <div class="mb-10">
                        <h4 class="text-xs font-black text-gold uppercase tracking-widest mb-2">Selected Package</h4>
                        <h2 class="text-2xl font-black text-forest font-poppins">{{ $booking->package->name ?? 'Package Reference Missing' }}</h2>
                        <p class="text-slate font-medium mt-2 leading-relaxed">{{ Str::limit($booking->package->description ?? '', 200) }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 p-8 bg-gray-50 rounded-[2rem] border border-gray-100">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Departure</p>
                            <p class="text-sm font-black text-forest font-poppins">{{ $booking->travel_date ? $booking->travel_date->format('d M, Y') : 'Unscheduled' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Duration</p>
                            <p class="text-sm font-black text-forest font-poppins">{{ $booking->package->duration ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Party Size</p>
                            <p class="text-sm font-black text-forest font-poppins">{{ $booking->travellers }} {{ Str::plural('Explorer', $booking->travellers) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Special Requests -->
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-luxury p-10">
                <h3 class="text-xs font-black text-forest font-poppins uppercase tracking-widest mb-4 border-l-4 border-gold pl-4">Custom Protocol & Requirements</h3>
                <div class="p-6 bg-red-50/30 border border-red-100 rounded-3xl min-h-[100px]">
                    <p class="text-slate font-medium italic">
                        {{ $booking->special_requests ?? 'No special tactical requirements reported for this mission.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Meta Sidebar -->
        <div class="space-y-8">
            <!-- Settlement State -->
            <div class="bg-forest rounded-[2.5rem] shadow-2xl p-10 text-white relative overflow-hidden">
                <div class="absolute -right-10 -top-10 opacity-10 text-[12rem]">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <h3 class="text-lg font-black mb-8 font-poppins border-b border-white/10 pb-4 italic uppercase tracking-widest">Settlement</h3>
                
                <div class="space-y-8 relative z-10">
                    <div>
                        <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-1">Total Due</p>
                        <p class="text-4xl font-black text-white font-poppins">₹{{ number_format($booking->total_amount) }}</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-white/60 uppercase tracking-widest">Financial State</span>
                            <span class="px-3 py-1 bg-white/20 rounded-full font-black uppercase tracking-widest text-[9px]">
                                {{ $booking->payment_status }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-white/60 uppercase tracking-widest">Settlement Path</span>
                            <span class="font-black italic">{{ $booking->payment_method ?? 'Awaiting Data' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-white/60 uppercase tracking-widest">Booking Status</span>
                            <span class="px-3 py-1 bg-gold text-forest rounded-full font-black uppercase tracking-widest text-[9px]">
                                {{ $booking->status }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-white/10">
                        <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-2 text-center">Reference Timestamp</p>
                        <p class="text-[10px] font-bold text-center opacity-80">{{ $booking->created_at->format('d M Y \a\t H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Agency Intelligence -->
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-luxury overflow-hidden">
                 <div class="px-8 py-5 bg-gray-50 border-b border-gray-100 italic">
                    <h3 class="text-xs font-black text-forest uppercase tracking-widest">Agency Intelligence</h3>
                </div>
                <div class="p-8 space-y-6">
                    <div class="flex items-center gap-4">
                        @if($booking->package->agency->logo ?? false)
                            <img src="{{ asset('storage/' . $booking->package->agency->logo) }}" class="w-12 h-12 object-cover rounded-xl shadow-sm">
                        @else
                            <div class="w-12 h-12 bg-forest text-white font-black rounded-xl flex items-center justify-center">{{ substr($booking->package->agency->name ?? 'A', 0, 1) }}</div>
                        @endif
                        <div>
                            <p class="text-sm font-bold text-forest">{{ $booking->package->agency->name ?? 'N/A' }}</p>
                            <p class="text-[10px] text-gold font-black uppercase tracking-widest">Verified Partner</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4 pt-4 border-t border-gray-50">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-forest mt-1 text-xs"></i>
                            <p class="text-[11px] text-slate font-medium">{{ $booking->package->agency->city ?? '' }}, {{ $booking->package->agency->state ?? '' }}</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-headset text-forest mt-1 text-xs"></i>
                            <p class="text-[11px] text-slate font-medium">{{ $booking->package->agency->phone ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ops Log -->
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-luxury p-8">
                <h3 class="text-[10px] font-black text-forest uppercase tracking-widest mb-4">Operations Log</h3>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-forest mt-1"></div>
                        <p class="text-[10px] font-bold text-slate"><span class="text-forest">ENTRY:</span> Reservation created via {{ $booking->booking_source }}</p>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-forest mt-1"></div>
                        <p class="text-[10px] font-bold text-slate"><span class="text-forest">STATE:</span> Protocol set to {{ $booking->status }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
