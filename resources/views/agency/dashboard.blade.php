@extends('agency.layouts.app')

@section('title', 'Agency Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-black text-forest font-poppins tracking-tight">Dashboard Overview</h1>
        <p class="text-slate text-sm font-medium mt-1">Welcome back, {{ $agency->name }}! Here's what's happening today.</p>
    </div>
    <div class="bg-white px-5 py-2.5 rounded-2xl shadow-soft border border-gray-100 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-forest/10 flex items-center justify-center text-forest">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate uppercase tracking-wider">Current Date</p>
            <p class="text-sm font-bold text-forest">{{ now()->format('M d, Y') }}</p>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Packages -->
    <div class="card group transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-2xl bg-forest/10 flex items-center justify-center text-forest text-xl group-hover:bg-forest group-hover:text-white transition-all">
                <i class="fas fa-suitcase-rolling"></i>
            </div>
            <span class="text-forest text-xs font-bold bg-forest/5 px-2 py-1 rounded-lg">Active</span>
        </div>
        <h3 class="text-3xl font-black text-forest-dark font-poppins mb-1">{{ $stats['total_packages'] }}</h3>
        <p class="text-slate text-sm font-bold uppercase tracking-wider">Total Packages</p>
    </div>

    <!-- Total Bookings -->
    <div class="card group transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-2xl bg-gold/10 flex items-center justify-center text-gold text-xl group-hover:bg-gold group-hover:text-white transition-all">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <span class="text-gold text-xs font-bold bg-gold/5 px-2 py-1 rounded-lg">+0%</span>
        </div>
        <h3 class="text-3xl font-black text-forest-dark font-poppins mb-1">{{ $stats['total_bookings'] }}</h3>
        <p class="text-slate text-sm font-bold uppercase tracking-wider">Total Bookings</p>
    </div>

    <!-- Pending Requests -->
    <div class="card group transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-2xl bg-forest-light/10 flex items-center justify-center text-forest-light text-xl group-hover:bg-forest-light group-hover:text-white transition-all">
                <i class="fas fa-clock"></i>
            </div>
            <span class="text-forest-light text-xs font-bold bg-forest-light/5 px-2 py-1 rounded-lg">Action Required</span>
        </div>
        <h3 class="text-3xl font-black text-forest-dark font-poppins mb-1">{{ $stats['pending_bookings'] }}</h3>
        <p class="text-slate text-sm font-bold uppercase tracking-wider">Pending Bookings</p>
    </div>

    <!-- Revenue -->
    <div class="card group transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-2xl bg-forest/10 flex items-center justify-center text-forest text-xl group-hover:bg-forest group-hover:text-white transition-all">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <span class="text-forest text-xs font-bold bg-forest/5 px-2 py-1 rounded-lg">Earnings</span>
        </div>
        <h3 class="text-3xl font-black text-forest-dark font-poppins mb-1">${{ number_format($stats['total_revenue'], 2) }}</h3>
        <p class="text-slate text-sm font-bold uppercase tracking-wider">Total Revenue</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
    <!-- Recent Bookings Table -->
    <div class="card h-full">
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-forest/10 flex items-center justify-center text-forest text-xl">
                    <i class="fas fa-history"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-forest-dark font-poppins">Recent Bookings</h3>
                    <p class="text-slate text-xs font-semibold">Keep track of your latest customers</p>
                </div>
            </div>
            <a href="{{ route('agency.bookings') }}" class="btn-primary py-2.5 px-6">View All</a>
        </div>
        
        <div class="overflow-x-auto custom-scrollbar">
            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Package</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-forest-dark/10 flex items-center justify-center text-forest font-bold text-sm">
                                    {{ substr($booking->user->name, 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-forest-dark">{{ $booking->user->name }}</span>
                                    <span class="text-[10px] text-slate font-semibold">{{ $booking->user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="font-semibold text-slate">{{ $booking->package->name }}</td>
                        <td class="font-semibold text-slate">{{ $booking->travel_date->format('M d, Y') }}</td>
                        <td>
                            @if($booking->status === 'confirmed')
                                <span class="badge badge-success">Confirmed</span>
                            @elseif($booking->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">{{ ucfirst($booking->status) }}</span>
                            @endif
                        </td>
                        <td class="font-bold text-forest">${{ number_format($booking->total_amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-slate font-semibold italic">No bookings found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
