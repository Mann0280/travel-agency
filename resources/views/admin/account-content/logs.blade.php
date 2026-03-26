@extends('admin.layouts.app')

@section('title', 'Password Reset Logs')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <header class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-[#17320b]">Password Reset Logs</h1>
                <p class="text-gray-600 text-sm">Monitor all password reset requests and activities</p>
            </div>
            <a href="{{ route('admin.account-content.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left mr-2"></i> Back to Manage My Account
            </a>
        </div>
    </header>

    <div class="card bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="px-4 py-3 text-xs font-bold text-gray-700 uppercase">Timestamp</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-700 uppercase">Email</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-700 uppercase">Status</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-700 uppercase">IP Address</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-700 uppercase">Device Info</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $log->created_at }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $log->email }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-[10px] font-bold rounded-full {{ $log->status === 'success' ? 'bg-green-100 text-green-700' : ($log->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ strtoupper($log->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 font-mono">{{ $log->ip_address }}</td>
                            <td class="px-4 py-3 text-xs text-gray-400 truncate max-w-[200px]" title="{{ $log->user_agent }}">
                                {{ $log->user_agent }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-gray-500 italic">No logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
