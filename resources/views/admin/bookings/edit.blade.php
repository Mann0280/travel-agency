@extends('admin.layouts.app')

@section('title', 'Modify Booking - #BK-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center text-sm text-gray-400 mb-2 font-medium">
                <a href="{{ route('admin.bookings.index') }}" class="hover:text-forest transition-colors text-xs font-bold uppercase tracking-widest">Voyage Pipeline</a>
                <i class="fas fa-chevron-right mx-2 text-[10px]"></i>
                <span class="text-gray-600 text-xs font-bold uppercase tracking-widest">Modify Manifest</span>
            </div>
            <h1 class="text-3xl font-black text-forest font-poppins italic">Edit Reservation</h1>
            <p class="text-gray-500 text-sm font-medium">Update itinerary, financials and status protocols</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="px-6 py-2 border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-all">
            <i class="fas fa-times mr-2"></i> Abort Changes
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-2xl">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="space-y-8 pb-32">
        @csrf
        @method('PUT')
        
        <!-- Itinerary & Logistics -->
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-luxury overflow-hidden">
            <div class="px-10 py-6 bg-forest italic">
                <h3 class="text-lg font-black text-white/90 font-poppins uppercase tracking-widest">Core Itinerary</h3>
            </div>
            <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Travel Date *</label>
                    <div class="relative">
                        <input type="date" name="travel_date" value="{{ old('travel_date', $booking->travel_date ? $booking->travel_date->format('Y-m-d') : '') }}" 
                               class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:border-forest focus:ring-2 focus:ring-forest/10 outline-none transition-all font-medium text-forest">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Explorer Count (Travellers)</label>
                    <input type="number" name="travellers" value="{{ old('travellers', $booking->travellers) }}" min="1"
                           class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:border-forest focus:ring-2 focus:ring-forest/10 outline-none transition-all font-medium text-forest">
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Special Tactical Requirements</label>
                    <textarea name="special_requests" rows="4" 
                              class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:border-forest focus:ring-2 focus:ring-forest/10 outline-none transition-all font-medium text-forest leading-relaxed resize-none" 
                              placeholder="Add any specific mission requirements here...">{{ old('special_requests', $booking->special_requests) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Financial & Protocol Settlement -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-luxury overflow-hidden">
                <div class="px-8 py-5 bg-gold italic">
                    <h3 class="text-base font-black text-forest font-poppins uppercase tracking-widest">Financial Settlement</h3>
                </div>
                <div class="p-8 space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Total Investment (₹)</label>
                        <input type="number" name="total_amount" value="{{ old('total_amount', $booking->total_amount) }}" 
                               class="w-full px-5 py-3 border border-gray-100 rounded-2xl font-black text-xl text-forest focus:ring-2 focus:ring-gold outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Settlement Path (Payment Method)</label>
                        <select name="payment_method" class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-forest focus:ring-2 focus:ring-forest/10 outline-none bg-white">
                            <option value="Credit Card" {{ old('payment_method', $booking->payment_method) == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="Debit Card" {{ old('payment_method', $booking->payment_method) == 'Debit Card' ? 'selected' : '' }}>Debit Card</option>
                            <option value="Net Banking" {{ old('payment_method', $booking->payment_method) == 'Net Banking' ? 'selected' : '' }}>Net Banking</option>
                            <option value="UPI" {{ old('payment_method', $booking->payment_method) == 'UPI' ? 'selected' : '' }}>UPI System</option>
                            <option value="Cash" {{ old('payment_method', $booking->payment_method) == 'Cash' ? 'selected' : '' }}>Physical Currency (Cash)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-luxury overflow-hidden">
                <div class="px-8 py-5 bg-gray-50 border-b border-gray-100 italic">
                    <h3 class="text-base font-black text-forest font-poppins uppercase tracking-widest">Protocols</h3>
                </div>
                <div class="p-8 space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Booking Lifecycle Phase</label>
                        <select name="status" class="w-full px-5 py-3 border border-gray-100 rounded-2xl font-black text-sm text-forest bg-white focus:ring-2 focus:ring-forest/10 outline-none">
                            <option value="pending" {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>Pending Protocol</option>
                            <option value="confirmed" {{ old('status', $booking->status) == 'confirmed' ? 'selected' : '' }}>Confirmed (Active)</option>
                            <option value="completed" {{ old('status', $booking->status) == 'completed' ? 'selected' : '' }}>Completed Manifest</option>
                            <option value="cancelled" {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>Cancelled / Aborted</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Financial State</label>
                        <select name="payment_status" class="w-full px-5 py-3 border border-gray-100 rounded-2xl font-black text-sm text-forest bg-white focus:ring-2 focus:ring-forest/10 outline-none">
                            <option value="pending" {{ old('payment_status', $booking->payment_status) == 'pending' ? 'selected' : '' }}>Awaiting Settlement</option>
                            <option value="paid" {{ old('payment_status', $booking->payment_status) == 'paid' ? 'selected' : '' }}>Fully Settled (Paid)</option>
                            <option value="refunded" {{ old('payment_status', $booking->payment_status) == 'refunded' ? 'selected' : '' }}>Refund Processed</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Footer -->
        <div class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-xl border-t border-gray-100 p-6 z-40">
            <div class="max-w-5xl mx-auto flex justify-end gap-4 px-4">
                <button type="button" onclick="window.history.back()" class="px-10 py-4 rounded-2xl font-black text-slate uppercase tracking-widest text-[10px] hover:bg-gray-100 transition-all">Discard Changes</button>
                <button type="submit" class="px-16 py-4 bg-forest text-white font-black rounded-2xl shadow-luxury hover:scale-[1.02] active:scale-[0.98] transition-all uppercase tracking-widest text-[10px]">
                    Update Manifest Protocol
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
