@extends('agency.layouts.app')

@section('title', 'Click Analytics')
@section('page_title', 'Click Analytics')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-black text-forest font-poppins tracking-tight">Click Analytics</h1>
        <p class="text-slate text-sm font-medium mt-1">Track WhatsApp and Call button clicks for your packages.</p>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Clicks -->
    <div class="card group transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-500 text-xl group-hover:bg-indigo-500 group-hover:text-white transition-all">
                <i class="fas fa-mouse-pointer"></i>
            </div>
            <span class="text-indigo-500 text-xs font-bold bg-indigo-500/5 px-2 py-1 rounded-lg">Interactions</span>
        </div>
        <h3 class="text-3xl font-black text-forest-dark font-poppins mb-1">{{ number_format($stats['total_clicks']) }}</h3>
        <p class="text-slate text-sm font-bold uppercase tracking-wider">Total Clicks</p>
    </div>

    <!-- WhatsApp Clicks -->
    <div class="card group transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 text-xl group-hover:bg-emerald-500 group-hover:text-white transition-all">
                <i class="fab fa-whatsapp"></i>
            </div>
            <span class="text-emerald-500 text-xs font-bold bg-emerald-500/5 px-2 py-1 rounded-lg">Leads</span>
        </div>
        <h3 class="text-3xl font-black text-forest-dark font-poppins mb-1">{{ number_format($stats['whatsapp_clicks']) }}</h3>
        <p class="text-slate text-sm font-bold uppercase tracking-wider">WhatsApp Clicks</p>
    </div>

    <!-- Call Clicks -->
    <div class="card group transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 text-xl group-hover:bg-blue-500 group-hover:text-white transition-all">
                <i class="fas fa-phone-alt"></i>
            </div>
            <span class="text-blue-500 text-xs font-bold bg-blue-500/5 px-2 py-1 rounded-lg">Calls</span>
        </div>
        <h3 class="text-3xl font-black text-forest-dark font-poppins mb-1">{{ number_format($stats['call_clicks']) }}</h3>
        <p class="text-slate text-sm font-bold uppercase tracking-wider">Call Clicks</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
    <!-- Click Data Table -->
    <div class="card h-full">
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-forest/10 flex items-center justify-center text-forest text-xl">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-forest-dark font-poppins">Recent Interactions</h3>
                    <p class="text-slate text-xs font-semibold">Detailed breakdown of button clicks</p>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="p-4 font-semibold text-sm text-forest-dark">Package</th>
                        <th class="p-4 font-semibold text-sm text-forest-dark">User Details</th>
                        <th class="p-4 font-semibold text-sm text-forest-dark">WhatsApp Clicks</th>
                        <th class="p-4 font-semibold text-sm text-forest-dark">Call Clicks</th>
                        <th class="p-4 font-semibold text-sm text-forest-dark">Total Clicks</th>
                        <th class="p-4 font-semibold text-sm text-forest-dark">Date & Time</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-medium text-slate">
                    @forelse($clickData as $click)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                        <td class="p-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-forest-dark">{{ $click->package->name ?? 'N/A' }}</span>
                                <span class="text-[10px] text-slate font-semibold">{{ $click->package->destination->name ?? '' }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-forest/10 flex items-center justify-center text-forest font-bold text-xs">
                                    {{ substr(optional($click->user)->name ?? 'G', 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-forest-dark text-sm">{{ optional($click->user)->name ?? 'Guest User' }}</span>
                                    <span class="text-[10px] text-slate font-semibold">{{ optional($click->user)->email ?? 'Anonymous' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <i class="fab fa-whatsapp text-emerald-500"></i>
                                <span class="font-bold text-forest-dark">{{ $click->whatsapp_clicks }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-phone-alt text-blue-500"></i>
                                <span class="font-bold text-forest-dark">{{ $click->call_clicks }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="badge badge-warning">{{ $click->button_clicks }}</span>
                        </td>
                        <td class="p-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-forest-dark text-sm">{{ $click->created_at->format('M d, Y') }}</span>
                                <span class="text-[10px] text-slate font-semibold">{{ $click->created_at->format('h:i A') }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-slate font-semibold italic">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-mouse-pointer text-3xl text-slate/30"></i>
                                <p>No click data available yet</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($clickData->hasPages())
            <div class="mt-4 px-4">
                {{ $clickData->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
