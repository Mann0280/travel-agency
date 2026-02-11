@extends('admin.layouts.app')

@section('title', 'Click Analytics')
@section('page_title', 'Click Analytics')

@section('content')
<style>
    .card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        padding: 1.5rem;
    }
    .stat-card {
        background: linear-gradient(135deg, #17320b 0%, #2a5016 100%);
        color: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-whatsapp { background-color: #25d366; color: white; }
    .badge-call { background-color: #22c55e; color: white; }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-[#17320b]">Click Analytics</h1>
            <p class="text-gray-600">Track WhatsApp and Call button clicks from package details pages</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Clicks -->
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm mb-1">Total Clicks</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['total_clicks']) }}</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-mouse-pointer text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- WhatsApp Clicks -->
        <div class="stat-card" style="background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm mb-1">WhatsApp Clicks</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['whatsapp_clicks']) }}</p>
                </div>
                <div class="stat-icon">
                    <i class="fab fa-whatsapp text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Call Clicks -->
        <div class="stat-card" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm mb-1">Call Clicks</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['call_clicks']) }}</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-phone text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Click Data Table -->
    <div class="card p-0 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-[#17320b]">Recent Button Clicks</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WhatsApp Clicks</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Call Clicks</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Clicks</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($clickData as $click)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $click->package->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $click->package->destination->name ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $click->user->name ?? 'Guest' }}</div>
                                <div class="text-xs text-gray-500">{{ $click->user->email ?? 'Anonymous' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="fab fa-whatsapp text-[#25d366] mr-2"></i>
                                    <span class="text-sm font-bold text-gray-900">{{ $click->whatsapp_clicks }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-[#22c55e] mr-2"></i>
                                    <span class="text-sm font-bold text-gray-900">{{ $click->call_clicks }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-[#a8894d]">{{ $click->button_clicks }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $click->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $click->created_at->format('h:i A') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                <i class="fas fa-chart-line text-4xl text-gray-300 mb-3"></i>
                                <p>No click data available yet.</p>
                                <p class="text-sm">Clicks will appear here when users interact with package buttons.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($clickData->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $clickData->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
