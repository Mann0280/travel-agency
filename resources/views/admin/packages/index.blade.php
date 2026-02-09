@extends('admin.layouts.app')

@section('title', 'Manage Packages')
@section('page_title', 'Packages Management')

@section('content')
@push('styles')
<link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator_modern.min.css" rel="stylesheet">
<style>
    .tabulator {
        border: none !important;
        background-color: transparent !important;
        font-family: 'Inter', sans-serif !important;
        height: auto !important;
        min-height: 400px;
    }
    .tabulator-header {
        background-color: #f8fafc !important; /* matches reference image more closely */
        color: #475569 !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        font-size: 0.7rem !important;
        border-bottom: 2px solid #e2e8f0 !important;
    }
    .tabulator-col {
        background-color: #f8fafc !important;
        border-right: none !important;
    }
    .tabulator-row {
        border-bottom: 1px solid #f1f5f9 !important;
        background-color: white !important;
        transition: background-color 0.2s !important;
        min-height: 80px !important;
        display: flex !important;
        align-items: center !important;
    }
    .tabulator-row:hover {
        background-color: #f8fafc !important;
    }
    .tabulator-cell {
        padding: 8px 16px !important;
        border: none !important;
        display: flex !important;
        align-items: center !important;
        font-size: 0.85rem !important;
        color: #334155 !important;
    }
    .tabulator-footer {
        background-color: white !important;
        border-top: 1px solid #e2e8f0 !important;
        padding: 12px !important;
        color: #64748b !important;
    }
</style>
@endpush

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Travel Packages</h1>
            <p class="text-gray-600">Manage all travel packages in your system</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <input type="text" id="tabulator-search" placeholder="Search packages..." 
                    class="w-full md:w-64 pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-gold/20 focus:border-gold transition-all duration-200 text-sm">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <a href="{{ route('admin.packages.create') }}" class="btn btn-primary px-4 py-2 rounded-lg font-bold flex items-center transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-plus mr-2"></i> Add Package
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-wrap gap-4 items-center">
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-gray-400 uppercase">Category:</span>
            <select id="filter-category" class="text-sm border-gray-200 rounded-lg focus:ring-gold focus:border-gold">
                <option value="">All Categories</option>
                <option value="adventure">Adventure</option>
                <option value="hill-station">Hill Station</option>
                <option value="cultural">Cultural</option>
                <option value="beach">Beach</option>
                <option value="desert">Desert</option>
                <option value="nature">Nature</option>
                <option value="trekking">Trekking</option>
                <option value="heritage">Heritage</option>
                <option value="religious">Religious</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-gray-400 uppercase">Status:</span>
            <select id="filter-status" class="text-sm border-gray-200 rounded-lg focus:ring-gold focus:border-gold">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <button id="reset-filters" class="text-xs font-bold text-gray-500 hover:text-gold transition-colors flex items-center gap-1 ml-auto">
            <i class="fas fa-redo"></i> Reset
        </button>
    </div>

    <!-- Packages Table -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Packages</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Price & Duration</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($packages as $package)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200">
                                    @if($package->image)
                                        <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <i class="fas fa-image text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 text-sm mb-1">{{ $package->name }}</div>
                                    @if($package->is_featured)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100 uppercase tracking-wide">
                                            <i class="fas fa-star text-[9px]"></i> Featured
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $package->location }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $package->destination->name ?? 'Unknown Destination' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-emerald-600">₹{{ number_format($package->price) }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $package->duration }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $colors = [
                                    'adventure' => 'bg-red-50 text-red-600 border-red-100',
                                    'beach' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'cultural' => 'bg-green-50 text-green-600 border-green-100',
                                    'desert' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                                    'hill-station' => 'bg-purple-50 text-purple-600 border-purple-100',
                                    'nature' => 'bg-teal-50 text-teal-600 border-teal-100'
                                ];
                                $colorClass = $colors[\Illuminate\Support\Str::slug($package->category)] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border {{ $colorClass }}">
                                {{ $package->category ?? 'General' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border {{ $package->status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-gray-50 text-gray-600 border-gray-100' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $package->status === 'active' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                    {{ $package->status }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border {{ $package->is_approved ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                                    <i class="fas {{ $package->is_approved ? 'fa-check-circle' : 'fa-clock' }} text-[9px]"></i>
                                    {{ $package->is_approved ? 'Approved' : 'Pending' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.packages.toggle-approval', $package) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ $package->is_approved ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }}" title="{{ $package->is_approved ? 'Revoke Approval' : 'Approve Package' }}">
                                        <i class="fas {{ $package->is_approved ? 'fa-ban' : 'fa-check' }}"></i>
                                    </button>
                                </form>
                                <a href="{{ route('admin.packages.edit', $package) }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this package?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center transition-colors">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-box-open text-2xl text-gray-300"></i>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900 mb-1">No Packages Found</h3>
                                <p class="text-xs text-gray-500">Get started by creating your first travel package.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($packages->hasPages())
        <div class="bg-white border-t border-gray-100 px-6 py-4">
            {{ $packages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
