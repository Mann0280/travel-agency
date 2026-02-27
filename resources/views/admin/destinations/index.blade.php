@extends('admin.layouts.app')

@section('title', 'Manage Destinations')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-gray-100 pb-8">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-forest transition-colors">Dashboard</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-gray-900 font-medium">Destinations</span>
            </div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight font-poppins">Manage Destinations</h1>
            <p class="text-gray-500 mt-1">Curate and organize your portal's travel destinations</p>
        </div>
        <div class="flex items-center gap-4">
            <!-- View Toggle -->
            <div class="hidden sm:flex bg-gray-100 p-1 rounded-xl border border-gray-200 shadow-inner">
                <button id="gridViewBtn" class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold transition-all duration-300 bg-white text-forest shadow-sm">
                    <i class="fas fa-th-large"></i>
                    <span>Grid</span>
                </button>
                <button id="listViewBtn" class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold transition-all duration-300 text-gray-500 hover:text-forest">
                    <i class="fas fa-list"></i>
                    <span>List</span>
                </button>
            </div>
            <a href="{{ route('admin.destinations.create') }}" class="btn btn-primary shadow-lg shadow-gold/20 hover:shadow-gold/40 transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-plus mr-2"></i>
                <span>Add Destination</span>
            </a>
        </div>
    </div>

    <!-- Stats Summary (Optional but looks premium) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Destinations</p>
            <p class="text-2xl font-black text-forest">{{ $destinations->total() }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Active Now</p>
            <p class="text-2xl font-black text-green-600">{{ $destinations->where('status', 1)->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Packages</p>
            <p class="text-2xl font-black text-blue-600">{{ $destinations->sum('packages_count') }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Uncategorized</p>
            <p class="text-2xl font-black text-amber-600">{{ $destinations->where('category', null)->count() }}</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div id="contentWrapper">
        <!-- Grid View -->
        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($destinations as $destination)
                <div class="group bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col h-full border-b-4 hover:border-b-gold">
                    <!-- Image Wrapper -->
                    <div class="relative h-56 overflow-hidden">
                        @if($destination->image)
                            <img src="{{ asset('storage/' . $destination->image) }}" 
                                 alt="{{ $destination->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-forest/5 to-gold/5 flex items-center justify-center">
                                <i class="fas fa-mountain text-6xl text-forest/10"></i>
                            </div>
                        @endif
                        
                        <!-- Overlay Badges -->
                        <div class="absolute top-4 left-4 right-4 flex justify-between items-start pointer-events-none">
                            @if($destination->status)
                                <span class="bg-green-500/90 backdrop-blur-md text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    Active
                                </span>
                            @else
                                <span class="bg-gray-500/90 backdrop-blur-md text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm">
                                    Draft
                                </span>
                            @endif
                            <span class="bg-white/90 backdrop-blur-md text-forest px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm">
                                <i class="fas fa-suitcase mr-1 text-gold"></i>
                                {{ $destination->packages_count }} PKG
                            </span>
                        </div>

                        <!-- Gradient for name -->
                        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-6 flex flex-col justify-end">
                            <h3 class="text-xl font-black text-white leading-tight font-poppins group-hover:text-gold transition-colors">{{ $destination->name }}</h3>
                            <p class="text-white/70 text-xs font-medium flex items-center gap-1">
                                <i class="fas fa-map-marker-alt text-[10px]"></i>
                                {{ $destination->location }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 flex flex-col flex-1">
                        <div class="mb-4">
                            @if($destination->category)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-forest/5 text-forest border border-forest/10 uppercase tracking-wider">
                                    <i class="fas fa-tag mr-1.5 opacity-50"></i>
                                    {{ $destination->category }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-gray-50 text-gray-400 border border-gray-100 uppercase tracking-wider">
                                    Uncategorized
                                </span>
                            @endif
                        </div>

                        <p class="text-gray-500 text-sm leading-relaxed line-clamp-2 mb-6 flex-1">
                            {{ $destination->description ?: 'No description provided for this destination yet.' }}
                        </p>

                        <!-- Actions - Fixed at Bottom -->
                        <div class="flex items-center gap-2 pt-4 border-t border-gray-50">
                            <a href="{{ route('admin.destinations.edit', $destination) }}" 
                               class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-50 text-gray-700 rounded-xl hover:bg-forest hover:text-white transition-all duration-300 font-bold text-xs uppercase tracking-wide">
                                <i class="fas fa-edit"></i>
                                Edit
                            </a>
                            <form action="{{ route('admin.destinations.destroy', $destination) }}" 
                                  method="POST" 
                                  class="contents"
                                  onsubmit="return confirm('Delete this destination? All linked packages will need update.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-2.5 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all duration-300">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Handled below -->
            @endforelse

            <!-- Add New Destination Card -->
            <a href="{{ route('admin.destinations.create') }}" 
               class="flex flex-col items-center justify-center p-8 rounded-3xl border-2 border-dashed border-gray-200 bg-gray-50/30 hover:bg-white hover:border-gold/50 hover:shadow-xl transition-all duration-500 text-center min-h-[400px] group">
                <div class="w-20 h-20 bg-white shadow-md rounded-full flex items-center justify-center text-gold mb-6 group-hover:scale-110 group-hover:rotate-90 transition-all duration-500">
                    <i class="fas fa-plus text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-gray-900 mb-2 font-poppins tracking-tight">Expand Your World</h3>
                <p class="text-gray-500 text-sm max-w-[200px] mb-8 leading-relaxed">Ready to add a new breathtaking location to the catalog?</p>
                <span class="px-6 py-2.5 bg-gray-900 text-white rounded-full text-xs font-black uppercase tracking-widest shadow-lg group-hover:bg-gold transition-colors">Create Now</span>
            </a>
        </div>

        @if($destinations->isEmpty())
             <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-gray-200">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300">
                    <i class="fas fa-map-marked-alt text-4xl"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2 font-poppins">No Destinations Found</h3>
                <p class="text-gray-500 mb-8 max-w-sm mx-auto">Your travel map is currently empty. Start by adding your first amazing destination.</p>
                <a href="{{ route('admin.destinations.create') }}" class="btn btn-primary px-8">
                    <i class="fas fa-plus mr-2"></i> Create First Destination
                </a>
            </div>
        @endif

        <!-- List View (Hidden by default) -->
        <div id="listView" class="hidden">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-gray-400">Destination</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-gray-400">Category</th>
                                <th class="px-6 py-4 text-center text-[10px] font-black uppercase tracking-widest text-gray-400">Packages</th>
                                <th class="px-6 py-4 text-center text-[10px] font-black uppercase tracking-widest text-gray-400">Status</th>
                                <th class="px-6 py-4 text-right text-[10px] font-black uppercase tracking-widest text-gray-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($destinations as $destination)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                                                @if($destination->image)
                                                    <img src="{{ asset('storage/' . $destination->image) }}" alt="" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-forest/5 text-forest/20">
                                                        <i class="fas fa-mountain"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 font-poppins">{{ $destination->name }}</div>
                                                <div class="text-[10px] font-medium text-gray-400 flex items-center gap-1 uppercase tracking-wide">
                                                    <i class="fas fa-map-marker-alt text-[8px] text-gold"></i>
                                                    {{ $destination->location }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($destination->category)
                                            <span class="text-xs font-bold text-gray-700">{{ $destination->category }}</span>
                                        @else
                                            <span class="text-xs text-gray-300 italic">No category</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center min-w-[32px] h-8 bg-forest/5 text-forest rounded-lg text-sm font-black">
                                            {{ $destination->packages_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($destination->status)
                                            <span class="w-2.5 h-2.5 rounded-full bg-green-500 inline-block ring-4 ring-green-100"></span>
                                        @else
                                            <span class="w-2.5 h-2.5 rounded-full bg-gray-300 inline-block ring-4 ring-gray-50"></span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end items-center gap-2">
                                            <a href="{{ route('admin.destinations.edit', $destination) }}" 
                                               class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-forest hover:text-white transition-all shadow-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.destinations.destroy', $destination) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Delete destination?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $destinations->links() }}
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gridViewBtn = document.getElementById('gridViewBtn');
        const listViewBtn = document.getElementById('listViewBtn');
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');

        function setView(view) {
            if (view === 'list') {
                gridView.classList.add('hidden');
                listView.classList.remove('hidden');
                
                listViewBtn.classList.add('bg-white', 'text-forest', 'shadow-sm');
                listViewBtn.classList.remove('text-gray-500');
                
                gridViewBtn.classList.remove('bg-white', 'text-forest', 'shadow-sm');
                gridViewBtn.classList.add('text-gray-500');
            } else {
                listView.classList.add('hidden');
                gridView.classList.remove('hidden');
                
                gridViewBtn.classList.add('bg-white', 'text-forest', 'shadow-sm');
                gridViewBtn.classList.remove('text-gray-500');
                
                listViewBtn.classList.remove('bg-white', 'text-forest', 'shadow-sm');
                listViewBtn.classList.add('text-gray-500');
            }
            localStorage.setItem('destinationViewPref', view);
        }

        gridViewBtn.addEventListener('click', () => setView('grid'));
        listViewBtn.addEventListener('click', () => setView('list'));

        // Restore preference
        const savedView = localStorage.getItem('destinationViewPref');
        if (savedView) setView(savedView);
    });
</script>
@endpush
@endsection
