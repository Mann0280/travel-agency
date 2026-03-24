@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-[#17320b]">Password Reset Logs</h2>
            <p class="text-gray-600">Track all password reset requests and their status</p>
        </div>
        <a href="{{ route('admin.account-content.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Agent</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $log->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @php
                                    $statusClass = match($log->status) {
                                        'success' => 'bg-green-100 text-green-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        'expired' => 'bg-yellow-100 text-yellow-800',
                                        default => 'bg-blue-100 text-blue-800',
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->ip_address }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y H:i') }}
                                <div class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <span class="truncate block max-w-xs" title="{{ $log->user_agent }}">{{ Str::limit($log->user_agent, 30) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-history text-4xl mb-3 block opacity-20"></i>
                                No password reset logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .card {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0,0,0,0.05);
    }
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
        border: 1px solid transparent;
        font-size: 0.875rem;
    }
    .btn-secondary { background-color: #17320b; color: white; }
    .btn-secondary:hover { background-color: #1a3a0f; transform: translateY(-1px); }
</style>
@endsection
