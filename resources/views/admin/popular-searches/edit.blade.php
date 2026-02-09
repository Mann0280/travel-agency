@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Popular Search</h2>
            <p class="text-gray-600">Update search ID: {{ $popularSearch->id }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.popular-searches.index') }}" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
            <form method="POST" action="{{ route('admin.popular-searches.destroy', $popularSearch) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this popular search? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-red-600 transition-all">
                    <i class="fas fa-trash mr-2"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Edit Search Details</h3>
            
            <form method="POST" action="{{ route('admin.popular-searches.update', $popularSearch) }}" id="searchForm">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <!-- From -->
                    <div>
                        <label for="from_city" class="block text-sm font-medium text-gray-700 mb-2">From (Departure City)</label>
                        <input type="text" id="from_city" name="from_city" 
                               value="{{ old('from_city', $popularSearch->from_city) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-forest" 
                               placeholder="e.g., Ahmedabad">
                        @error('from_city')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- To -->
                    <div>
                        <label for="to_city" class="block text-sm font-medium text-gray-700 mb-2">To (Destination)</label>
                        <input type="text" id="to_city" name="to_city" 
                               value="{{ old('to_city', $popularSearch->to_city) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-forest" 
                               placeholder="e.g., Spiti, Manali">
                        @error('to_city')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Display Text -->
                    <div>
                        <label for="display_text" class="block text-sm font-medium text-gray-700 mb-2">Display Text</label>
                        <input type="text" id="display_text" name="display_text" 
                               value="{{ old('display_text', $popularSearch->display_text) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-forest" 
                               placeholder="Text shown on the button"
                               maxlength="50">
                        <p class="text-xs text-gray-500 mt-1">
                            Max 50 characters. <span id="charCount" class="text-gray-400">{{ 50 - strlen($popularSearch->display_text) }}</span> characters remaining
                        </p>
                        @error('display_text')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Order -->
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                        <input type="number" id="order" name="order" 
                               value="{{ old('order', $popularSearch->order) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-forest" 
                               min="1" required>
                        <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
                        @error('order')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Status</label>
                        <div class="flex items-center space-x-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $popularSearch->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($popularSearch->status) }}
                            </span>
                            <form method="POST" action="{{ route('admin.popular-searches.toggle-status', $popularSearch) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-gold hover:underline">
                                    (Toggle Status)
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Clicks -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Click Count</label>
                        <div class="flex items-center space-x-2">
                            <span class="text-gray-700">{{ $popularSearch->clicks }} clicks</span>
                            @if($popularSearch->clicks > 0)
                                <form method="POST" action="{{ route('admin.popular-searches.reset-clicks', $popularSearch) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm text-gray-500 hover:text-red-600">
                                        (Reset to zero)
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Created Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Created</label>
                        <p class="text-gray-700">{{ $popularSearch->created_at->format('F j, Y, g:i a') }}</p>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="pt-4 flex space-x-3">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-forest to-forest-dark text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg transition-all">
                            <i class="fas fa-save mr-2"></i> Update Search
                        </button>
                        <a href="{{ route('admin.popular-searches.index') }}" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-200 transition-all">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Preview -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Live Preview</h3>
            
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Preview updates as you type:</p>
                <button type="button" onclick="updatePreview()" class="bg-gold text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gold/80 transition-all">
                    <i class="fas fa-sync mr-2"></i> Update Preview
                </button>
            </div>
            
            <!-- Preview Container -->
            <div class="bg-champagne border border-gold/20 p-6 rounded-lg">
                <div class="mb-3">
                    <p class="text-xs text-gray-500 mb-2">Popular searches:</p>
                    <div class="flex flex-wrap gap-2">
                        <div id="previewButton" class="bg-white border border-gold text-gray-900 px-3 py-2 rounded text-sm hover:bg-gold hover:text-white transition inline-flex items-center cursor-default">
                            @if($popularSearch->from_city && $popularSearch->to_city)
                                <i class="fas fa-arrow-right mr-1 text-xs"></i>{{ $popularSearch->display_text }}
                            @elseif($popularSearch->from_city)
                                <i class="fas fa-location-arrow mr-1 text-xs"></i>{{ $popularSearch->display_text }}
                            @elseif($popularSearch->to_city)
                                <i class="fas fa-map-marker-alt mr-1 text-xs"></i>{{ $popularSearch->display_text }}
                            @else
                                <i class="fas fa-search mr-1 text-xs"></i>{{ $popularSearch->display_text }}
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-2">Search Details:</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex">
                            <span class="w-24 text-gray-600">From:</span>
                            <span id="previewFrom" class="font-medium">{{ $popularSearch->from_city ?: '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-24 text-gray-600">To:</span>
                            <span id="previewTo" class="font-medium">{{ $popularSearch->to_city ?: '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-24 text-gray-600">Display Text:</span>
                            <span id="previewDisplayText" class="font-medium">{{ $popularSearch->display_text }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-24 text-gray-600">Generated URL:</span>
                            <span id="previewUrl" class="font-medium text-blue-600 text-xs break-all">
                                /search?{{ http_build_query($popularSearch->url_parameters) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Current Stats -->
            <div class="mt-6">
                <h4 class="font-medium text-gray-900 mb-2">Current Stats:</h4>
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <div class="text-xs text-gray-600">Status</div>
                        <div class="text-sm font-medium">{{ ucfirst($popularSearch->status) }}</div>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <div class="text-xs text-gray-600">Clicks</div>
                        <div class="text-sm font-medium">{{ $popularSearch->clicks }}</div>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <div class="text-xs text-gray-600">Order</div>
                        <div class="text-sm font-medium">{{ $popularSearch->order }}</div>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <div class="text-xs text-gray-600">ID</div>
                        <div class="text-sm font-medium">{{ $popularSearch->id }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Character counter for display text
document.getElementById('display_text').addEventListener('input', function() {
    const maxLength = 50;
    const currentLength = this.value.length;
    const remaining = maxLength - currentLength;
    
    const charCount = document.getElementById('charCount');
    charCount.textContent = remaining;
    
    if (remaining < 0) {
        charCount.className = 'text-red-500';
    } else if (remaining < 10) {
        charCount.className = 'text-yellow-500';
    } else {
        charCount.className = 'text-gray-400';
    }
});

// Preview functionality
function updatePreview() {
    const from = document.getElementById('from_city').value.trim();
    const to = document.getElementById('to_city').value.trim();
    const displayText = document.getElementById('display_text').value.trim();
    
    // Generate display text if empty
    let finalDisplayText = displayText;
    if (!finalDisplayText) {
        if (from && to) {
            finalDisplayText = from + ' → ' + to;
        } else if (from) {
            finalDisplayText = 'From ' + from;
        } else if (to) {
            finalDisplayText = 'To ' + to;
        }
    }
    
    // Update preview elements
    document.getElementById('previewFrom').textContent = from || '-';
    document.getElementById('previewTo').textContent = to || '-';
    document.getElementById('previewDisplayText').textContent = finalDisplayText || '-';
    
    // Generate URL
    const urlParams = new URLSearchParams();
    if (from) urlParams.append('from', from);
    if (to) urlParams.append('to', to);
    if (from || to) urlParams.append('search', '1');
    
    const url = `/search?${urlParams.toString()}`;
    document.getElementById('previewUrl').textContent = url;
    document.getElementById('previewUrl').setAttribute('title', url);
    
    // Update preview button
    const previewButton = document.getElementById('previewButton');
    previewButton.innerHTML = '';
    
    if (from && to) {
        previewButton.innerHTML = `<i class="fas fa-arrow-right mr-1 text-xs"></i>${finalDisplayText}`;
    } else if (from) {
        previewButton.innerHTML = `<i class="fas fa-location-arrow mr-1 text-xs"></i>${finalDisplayText}`;
    } else if (to) {
        previewButton.innerHTML = `<i class="fas fa-map-marker-alt mr-1 text-xs"></i>${finalDisplayText}`;
    } else {
        previewButton.innerHTML = `<i class="fas fa-search mr-1 text-xs"></i>Custom Search`;
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Auto-update preview on input
    ['from_city', 'to_city', 'display_text'].forEach(id => {
        document.getElementById(id).addEventListener('input', function() {
            setTimeout(updatePreview, 300);
        });
    });
    
    // Form validation
    document.getElementById('searchForm').addEventListener('submit', function(e) {
        const from = document.getElementById('from_city').value.trim();
        const to = document.getElementById('to_city').value.trim();
        
        if (!from && !to) {
            e.preventDefault();
            alert('Please fill either "From" or "To" field (or both)');
            return false;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
        submitBtn.disabled = true;
    });
});
</script>
@endsection
