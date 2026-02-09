@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header with Add Button -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Manage Popular Searches</h2>
            <p class="text-gray-600">Manage the popular search suggestions on homepage</p>
        </div>
        <a href="{{ route('admin.popular-searches.create') }}" class="bg-gradient-to-r from-forest to-forest-dark text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <i class="fas fa-plus mr-2"></i> Add New Search
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                    <i class="fas fa-search text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSearches }}</p>
                    <p class="text-gray-600 text-sm">Total Searches</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeSearches }}</p>
                    <p class="text-gray-600 text-sm">Active Searches</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center mr-4">
                    <i class="fas fa-pause-circle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $inactiveSearches }}</p>
                    <p class="text-gray-600 text-sm">Inactive Searches</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center mr-4">
                    <i class="fas fa-mouse-pointer text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalClicks }}</p>
                    <p class="text-gray-600 text-sm">Total Clicks</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Searches Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Search Details</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Display Text</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Clicks</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if($searches->count() > 0)
                        @foreach($searches as $search)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full bg-gold/10 flex items-center justify-center">
                                                <i class="fas fa-search text-gold"></i>
                                            </div>
                                        </div>
                                        <div>
                                            @if($search->from_city)
                                                <div class="text-sm font-medium text-gray-900">
                                                    From: {{ $search->from_city }}
                                                </div>
                                            @endif
                                            @if($search->to_city)
                                                <div class="text-sm font-medium text-gray-900">
                                                    To: {{ $search->to_city }}
                                                </div>
                                            @endif
                                            @if(!$search->from_city && !$search->to_city)
                                                <div class="text-sm text-gray-500">Custom Search</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $search->display_text }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form method="POST" action="{{ route('admin.popular-searches.toggle-status', $search) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 rounded-full text-xs font-bold {{ $search->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} hover:opacity-80 transition-opacity">
                                            {{ ucfirst($search->status) }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $search->order }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $search->clicks }}
                                        </span>
                                        @if($search->clicks > 0)
                                            <form method="POST" action="{{ route('admin.popular-searches.reset-clicks', $search) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs text-gray-500 hover:text-red-600 transition-colors" title="Reset click count">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $search->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('admin.popular-searches.edit', $search) }}" class="text-gold hover:text-forest transition-colors">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.popular-searches.destroy', $search) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this popular search? This action cannot be undone.')">
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
                            <td colspan="7" class="px-6 py-12 text-center">
                                <i class="fas fa-search text-6xl mb-4 text-gray-300"></i>
                                <p class="text-lg text-gray-500 font-medium">No popular searches found</p>
                                <p class="text-sm text-gray-400 mt-1">Add your first popular search to get started</p>
                                <a href="{{ route('admin.popular-searches.create') }}" class="inline-block mt-4 bg-gradient-to-r from-forest to-forest-dark text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg transition-all">
                                    <i class="fas fa-plus mr-2"></i> Add Search
                                </a>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Preview Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Frontend Preview</h3>
        <p class="text-gray-600 mb-4">This is how your popular searches will appear on the homepage:</p>
        
        <div class="bg-champagne border border-gold/20 p-6 rounded-lg">
            <div class="mb-3">
                <p class="text-xs text-gray-500 mb-2">Popular searches:</p>
                <div class="flex flex-wrap gap-2">
                    @php
                        $activeSearches = $searches->where('status', 'active')->take(6);
                    @endphp
                    
                    @if($activeSearches->count() > 0)
                        @foreach($activeSearches as $search)
                            <a href="#" class="bg-white border border-gold text-gray-900 px-3 py-2 rounded text-sm hover:bg-gold hover:text-white transition inline-flex items-center">
                                @if($search->from_city && $search->to_city)
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    {{ $search->from_city }} → {{ $search->to_city }}
                                @elseif($search->from_city)
                                    <i class="fas fa-location-arrow mr-1 text-xs"></i>
                                    From {{ $search->from_city }}
                                @elseif($search->to_city)
                                    <i class="fas fa-map-marker-alt mr-1 text-xs"></i>
                                    To {{ $search->to_city }}
                                @else
                                    <i class="fas fa-search mr-1 text-xs"></i>
                                    {{ $search->display_text }}
                                @endif
                            </a>
                        @endforeach
                    @else
                        <div class="text-center py-4 text-gray-500 w-full">
                            <i class="fas fa-search text-2xl mb-2"></i>
                            <p>No active popular searches</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="text-xs text-gray-500 mt-4">
                <i class="fas fa-info-circle mr-1"></i>
                Only active searches appear in this preview. Inactive searches are hidden.
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-xl mt-1"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-blue-800">Popular Searches Guide</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Only active searches will appear on the homepage</li>
                        <li>Searches are displayed in order (1, 2, 3, etc.)</li>
                        <li>Click count tracks how many times users click on each search</li>
                        <li>You can have searches with only "From", only "To", or both</li>
                        <li>Display text is shown to users in the search buttons</li>
                        <li>Keep display text concise and descriptive</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
