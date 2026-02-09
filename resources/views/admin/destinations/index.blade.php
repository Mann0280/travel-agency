@extends('admin.layouts.app')

@section('title', 'Manage Destinations')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-forest font-poppins">Manage Destinations</h1>
            <p class="text-slate mt-1">Organize destinations and their categories</p>
        </div>
        <a href="{{ route('admin.destinations.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            <span>Add Destination</span>
        </a>
    </div>

    <!-- View Toggle -->
    <div class="flex justify-end">
        <div class="inline-flex rounded-xl overflow-hidden border-2 border-forest/20 shadow-soft">
            <button id="gridViewBtn" class="px-5 py-2.5 bg-forest text-white font-semibold transition-all duration-300">
                <i class="fas fa-th-large mr-2"></i>Grid
            </button>
            <button id="listViewBtn" class="px-5 py-2.5 bg-white text-forest font-semibold transition-all duration-300 hover:bg-champagne">
                <i class="fas fa-list mr-2"></i>List
            </button>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Grid View -->
    <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($destinations as $destination)
            <div class="card group">
                <!-- Image -->
                <div class="relative h-48 overflow-hidden rounded-xl mb-4">
                    @if($destination->image)
                        <img src="{{ asset('storage/' . $destination->image) }}" 
                             alt="{{ $destination->name }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-forest/10 to-gold/10 flex items-center justify-center">
                            <i class="fas fa-map-marked-alt text-5xl text-forest/30"></i>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="absolute top-3 right-3">
                        @if($destination->status)
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle mr-1"></i>Active
                            </span>
                        @else
                            <span class="badge badge-danger">
                                <i class="fas fa-times-circle mr-1"></i>Inactive
                            </span>
                        @endif
                    </div>
                    
                    <!-- Gradient Overlay -->
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                        <h3 class="text-white font-bold text-lg font-poppins">{{ $destination->name }}</h3>
                        <p class="text-white/80 text-sm">{{ $destination->location }}</p>
                    </div>
                </div>

                <!-- Content -->
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        @if($destination->category)
                            <span class="badge badge-info">
                                <i class="fas fa-tag mr-1"></i>{{ $destination->category }}
                            </span>
                        @else
                            <span class="badge badge-info">
                                <i class="fas fa-tag mr-1"></i>Uncategorized
                            </span>
                        @endif
                        <span class="text-slate text-sm font-semibold">
                            <i class="fas fa-suitcase-rolling mr-1 text-gold"></i>
                            {{ $destination->packages_count }} Packages
                        </span>
                    </div>

                    @if($destination->description)
                        <p class="text-slate text-sm line-clamp-2">{{ Str::limit($destination->description, 100) }}</p>
                    @endif

                    <!-- Actions -->
                    <div class="flex gap-2 pt-2">
                        <a href="{{ route('admin.destinations.edit', $destination) }}" 
                           class="flex-1 px-4 py-2 bg-forest/10 text-forest rounded-xl hover:bg-forest hover:text-white transition-all duration-300 text-center font-semibold text-sm">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.destinations.destroy', $destination) }}" 
                              method="POST" 
                              class="flex-1"
                              onsubmit="return confirm('Are you sure you want to delete this destination?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all duration-300 font-semibold text-sm">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-map-marked-alt text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-600 mb-2">No Destinations Found</h3>
                <p class="text-gray-500 mb-4">Get started by creating your first destination</p>
                <a href="{{ route('admin.destinations.create') }}" class="btn-primary inline-flex">
                    <i class="fas fa-plus"></i>
                    <span>Add Destination</span>
                </a>
            </div>
        @endforelse

        <!-- Add New Card -->
        @if($destinations->count() > 0)
            <a href="{{ route('admin.destinations.create') }}" 
               class="card bg-gradient-to-br from-forest/5 to-gold/5 border-2 border-dashed border-forest/30 hover:border-forest hover:bg-forest/10 transition-all duration-300 flex flex-col items-center justify-center p-12 text-center group">
                <div class="w-16 h-16 bg-forest rounded-full flex items-center justify-center text-white mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-plus text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-forest mb-2 font-poppins">Add New Destination</h3>
                <p class="text-slate text-sm">Create a new destination category</p>
            </a>
        @endif
    </div>

    <!-- List View (Hidden by default) -->
    <div id="listView" class="hidden">
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="text-left">ID</th>
                            <th class="text-left">Destination</th>
                            <th class="text-left">Category</th>
                            <th class="text-center">Packages</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($destinations as $destination)
                            <tr>
                                <td class="font-bold text-forest">{{ $destination->id }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if($destination->image)
                                            <img src="{{ asset('storage/' . $destination->image) }}" 
                                                 alt="{{ $destination->name }}" 
                                                 class="w-12 h-12 object-cover rounded-lg">
                                        @else
                                            <div class="w-12 h-12 bg-gradient-to-br from-forest/10 to-gold/10 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-map-marked-alt text-forest/30"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-semibold text-forest">{{ $destination->name }}</div>
                                            <div class="text-sm text-slate">{{ $destination->location }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($destination->category)
                                        <span class="badge badge-info">{{ $destination->category }}</span>
                                    @else
                                        <span class="text-gray-400 text-sm">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="font-bold text-forest">{{ $destination->packages_count }}</span>
                                </td>
                                <td class="text-center">
                                    @if($destination->status)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.destinations.edit', $destination) }}" 
                                           class="px-3 py-1.5 bg-forest/10 text-forest rounded-lg hover:bg-forest hover:text-white transition-all text-sm font-semibold">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.destinations.destroy', $destination) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this destination?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all text-sm font-semibold">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <i class="fas fa-map-marked-alt text-5xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">No destinations found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // View toggle functionality
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');

    gridViewBtn.addEventListener('click', function() {
        this.classList.remove('bg-white', 'text-forest');
        this.classList.add('bg-forest', 'text-white');
        listViewBtn.classList.remove('bg-forest', 'text-white');
        listViewBtn.classList.add('bg-white', 'text-forest');
        gridView.classList.remove('hidden');
        listView.classList.add('hidden');
    });

    listViewBtn.addEventListener('click', function() {
        this.classList.remove('bg-white', 'text-forest');
        this.classList.add('bg-forest', 'text-white');
        gridViewBtn.classList.remove('bg-forest', 'text-white');
        gridViewBtn.classList.add('bg-white', 'text-forest');
        gridView.classList.add('hidden');
        listView.classList.remove('hidden');
    });
</script>
@endpush
@endsection
