@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header with Add Button -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Manage Hero Banners</h2>
            <p class="text-gray-600">Manage the banners displayed on the homepage hero section</p>
        </div>
        <a href="{{ route('admin.banner.create') }}" class="bg-gradient-to-r from-forest to-forest-dark text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <i class="fas fa-plus mr-2"></i> Add New Banner
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                    <i class="fas fa-image text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalBanners }}</p>
                    <p class="text-gray-600 text-sm">Total Banners</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeBanners }}</p>
                    <p class="text-gray-600 text-sm">Active Banners</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center mr-4">
                    <i class="fas fa-pause-circle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $inactiveBanners }}</p>
                    <p class="text-gray-600 text-sm">Inactive Banners</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center mr-4">
                    <i class="fas fa-sort-numeric-up text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">3</p>
                    <p class="text-gray-600 text-sm">Default Display</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Banners Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title & Subtitle</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if($banners->count() > 0)
                        @foreach($banners as $banner)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="w-20 h-16 rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $banner->image) }}" 
                                             alt="{{ $banner->title }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $banner->title }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $banner->description }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <form method="POST" action="{{ route('admin.banner.toggle-status', $banner) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 rounded-full text-xs font-bold {{ $banner->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} hover:opacity-80 transition-opacity cursor-pointer">
                                            {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $banner->sort_order }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $banner->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.banner.edit', $banner) }}" 
                                           class="text-gold hover:text-forest transition-colors">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.banner.destroy', $banner) }}" class="inline" onsubmit="return confirmDelete()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-image text-4xl mb-2 text-gray-300"></i>
                                <p class="text-lg">No banners found</p>
                                <p class="text-sm mt-1">Add your first banner to get started</p>
                                <a href="{{ route('admin.banner.create') }}" class="inline-block mt-4 bg-gradient-to-r from-forest to-forest-dark text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg transition-all">
                                    <i class="fas fa-plus mr-2"></i> Add Banner
                                </a>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    return confirm('Are you sure you want to delete this banner? This action cannot be undone.');
}
</script>
@endsection
