@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header with Search and Add Button -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Upcoming Travel Stories</h1>
            <p class="text-gray-600">Manage upcoming travel stories displayed on homepage</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <input type="text" placeholder="Search stories..." class="form-input pl-10" id="searchInput">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <a href="{{ route('admin.stories.create') }}" class="bg-gradient-to-r from-forest to-forest-dark text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg transition-all">
                <i class="fas fa-plus mr-2"></i> Add Story
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-blue-100">
            <div class="flex items-center">
                <div class="rounded-lg bg-blue-100 p-3 mr-4">
                    <i class="fas fa-images text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Stories</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $totalStories }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-green-100">
            <div class="flex items-center">
                <div class="rounded-lg bg-green-100 p-3 mr-4">
                    <i class="fas fa-eye text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Views</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($totalViews) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-purple-100">
            <div class="flex items-center">
                <div class="rounded-lg bg-purple-100 p-3 mr-4">
                    <i class="fas fa-mouse-pointer text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Clicks</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($totalClicks) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-yellow-100">
            <div class="flex items-center">
                <div class="rounded-lg bg-yellow-100 p-3 mr-4">
                    <i class="fas fa-star text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Featured</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $featuredCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <div class="flex flex-wrap gap-3">
            <select class="form-select w-full md:w-auto" id="statusFilter">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <select class="form-select w-full md:w-auto" id="featuredFilter">
                <option value="">All Stories</option>
                <option value="1">Featured Only</option>
                <option value="0">Non-Featured</option>
            </select>
            <button class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg transition-colors" onclick="resetFilters()">
                <i class="fas fa-redo mr-2"></i> Reset Filters
            </button>
        </div>
    </div>

    <!-- Stories Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
            <table id="storiesTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clicks</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($stories as $story)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $story->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="{{ asset('storage/' . $story->image) }}" alt="{{ $story->destination }}"
                                    class="w-12 h-12 object-cover rounded-lg">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $story->destination }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                    {{ $story->date->format('d M') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($story->package)
                                    <a href="{{ route('admin.packages.edit', $story->package) }}"
                                        class="text-gold hover:underline text-sm">
                                        {{ $story->package->name }}
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($story->views) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($story->clicks) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $story->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $story->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($story->is_featured)
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i> Featured
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.stories.edit', $story) }}"
                                        class="text-blue-600 hover:text-blue-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="viewStory({{ $story->id }})"
                                        class="text-purple-600 hover:text-purple-900" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form action="{{ route('admin.stories.destroy', $story) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this story?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-images text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-lg text-gray-600 font-medium">No stories found</p>
                                    <p class="text-sm text-gray-500 mb-4">Add your first story to get started</p>
                                    <a href="{{ route('admin.stories.create') }}" class="bg-gradient-to-r from-forest to-forest-dark text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg transition-all">
                                        <i class="fas fa-plus mr-2"></i> Add Story
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('featuredFilter').value = '';
    document.getElementById('searchInput').value = '';
    
    const rows = document.querySelectorAll('#storiesTable tbody tr');
    rows.forEach(row => row.style.display = '');
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#storiesTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Filter functionality
document.getElementById('statusFilter').addEventListener('change', filterTable);
document.getElementById('featuredFilter').addEventListener('change', filterTable);

function filterTable() {
    const status = document.getElementById('statusFilter').value.toLowerCase();
    const featured = document.getElementById('featuredFilter').value;
    const rows = document.querySelectorAll('#storiesTable tbody tr');
    
    rows.forEach(row => {
        if (row.cells.length < 10) return; // Skip empty state row
        
        const rowStatus = row.cells[7].textContent.toLowerCase().includes('active') ? 'active' : 'inactive';
        const rowFeatured = row.cells[8].innerHTML.includes('Featured');
        
        let showRow = true;
        
        if (status && rowStatus !== status) {
            showRow = false;
        }
        
        if (featured === '1' && !rowFeatured) {
            showRow = false;
        } else if (featured === '0' && rowFeatured) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

function viewStory(id) {
    window.location.href = `/admin/stories/${id}/edit`;
}
</script>
@endsection
