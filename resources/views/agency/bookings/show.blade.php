@extends('agency.layouts.app')

@section('title', 'Booking Details')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-1 h-8 bg-forest rounded-full"></div>
                <h1 class="text-2xl font-black text-forest font-poppins">Booking Details</h1>
            </div>
            <p class="text-slate text-sm font-medium ml-4">Reservation #BK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>
        <a href="{{ route('agency.bookings') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-gray-50 text-slate font-bold rounded-xl border border-gray-200 hover:bg-gray-100 transition-all duration-300">
            <i class="fas fa-arrow-left text-sm"></i>
            Back to Pipeline
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-8">
        <!-- Customer Info -->
        <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-lg">
            <h3 class="text-lg font-black text-forest font-poppins uppercase tracking-tight mb-6 border-b border-gray-100 pb-4">Customer Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-slate uppercase tracking-widest mb-1">Full Name</label>
                    <p class="text-sm font-bold text-forest-dark">{{ $booking->user->name }}</p>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate uppercase tracking-widest mb-1">Email Address</label>
                    <p class="text-sm font-bold text-forest-dark">{{ $booking->user->email }}</p>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate uppercase tracking-widest mb-1">Phone Number</label>
                    <p class="text-sm font-bold text-forest-dark">{{ $booking->user->phone ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>

        <!-- Package Info -->
        <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-lg">
            <h3 class="text-lg font-black text-forest font-poppins uppercase tracking-tight mb-6 border-b border-gray-100 pb-4">Package Details</h3>
            <div class="flex items-start gap-6">
                @if($booking->package->image)
                    <img src="{{ asset('storage/' . $booking->package->image) }}" class="w-32 h-24 object-cover rounded-xl shadow-md">
                @endif
                <div>
                    <h4 class="text-base font-bold text-forest font-poppins">{{ $booking->package->name }}</h4>
                    <p class="text-xs text-slate font-medium mt-1">{{ $booking->package->destination->name }}</p>
                    <div class="flex items-center gap-4 mt-4">
                        <div>
                            <span class="block text-[10px] font-black text-slate uppercase tracking-widest">Travel Date</span>
                            <span class="text-sm font-bold text-forest-dark">{{ $booking->travel_date->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-slate uppercase tracking-widest">Duration</span>
                            <span class="text-sm font-bold text-forest-dark">{{ $booking->package->duration }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($booking->special_requests)
        <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-lg">
            <h3 class="text-lg font-black text-forest font-poppins uppercase tracking-tight mb-4">Special Requests</h3>
            <p class="text-sm text-slate font-medium leading-relaxed">{{ $booking->special_requests }}</p>
        </div>
        @endif
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-lg text-center">
            <h4 class="text-xs font-black text-slate uppercase tracking-widest mb-4">Current Status</h4>
            @php
                $statusColors = [
                    'confirmed' => 'bg-green-50 text-green-600 border-green-200',
                    'pending' => 'bg-yellow-50 text-yellow-600 border-yellow-200',
                    'cancelled' => 'bg-red-50 text-red-600 border-red-200',
                ];
                $statusColor = $statusColors[$booking->status] ?? 'bg-gray-50 text-gray-600 border-gray-200';
            @endphp
            <div class="inline-flex items-center gap-2 px-6 py-3 rounded-full border {{ $statusColor }} text-lg font-black font-poppins mb-8">
                {{ strtoupper($booking->status) }}
            </div>

            <div class="border-t border-gray-100 pt-8">
                <h4 class="text-xs font-black text-slate uppercase tracking-widest mb-2">Total Amount</h4>
                <p class="text-3xl font-black text-forest font-poppins">${{ number_format($booking->total_amount, 2) }}</p>
                <p class="text-[10px] text-slate font-bold uppercase mt-1">For {{ $booking->number_of_travelers }} Traveler(s)</p>
            </div>

            <div class="mt-8 space-y-3">
                @if($booking->status === 'pending')
                    <form action="{{ route('agency.bookings.status', $booking) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit" class="w-full py-4 bg-forest text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300">
                            Confirm Reservation
                        </button>
                    </form>
                @endif

                @if($booking->status !== 'cancelled')
                    <form action="{{ route('agency.bookings.status', $booking) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="w-full py-4 bg-red-50 text-red-600 font-bold uppercase tracking-widest text-xs rounded-xl hover:bg-red-100 transition-all duration-300">
                            Cancel Booking
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
