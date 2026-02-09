@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header with Breadcrumb -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.stories.index') }}" class="text-gold hover:underline">Stories</a>
                <i class="fas fa-chevron-right mx-2"></i>
                <span>Edit Story</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Story</h1>
            <p class="text-gray-600">Update story information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.stories.index') }}" class="bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-50 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
            <form action="{{ route('admin.stories.toggle-status', $story) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-bold transition-all">
                    <i class="fas fa-toggle-on mr-2"></i> Toggle Status
                </button>
            </form>
        </div>
    </div>
    
    <!-- Story Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.stories.update', $story) }}" method="POST" enctype="multipart/form-data" id="storyForm" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Destination Name *</label>
                        <input type="text" name="destination" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent" 
                               value="{{ old('destination', $story->destination) }}" required>
                        @error('destination')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                        <input type="date" name="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent" 
                               value="{{ old('date', $story->date->format('Y-m-d')) }}" required>
                        @error('date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Associated Package</label>
                        <select name="package_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent">
                            <option value="">Select Package (Optional)</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}" {{ old('package_id', $story->package_id) == $package->id ? 'selected' : '' }}>
                                    {{ $package->name }} @if($package->destination) ({{ $package->destination->name }}) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('package_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center pt-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" 
                                   class="rounded border-gray-300 text-forest focus:ring-forest mr-2"
                                   {{ old('is_featured', $story->is_featured) ? 'checked' : '' }}>
                            <span class="text-gray-900 font-medium">Featured Story</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Image -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Image</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $story->image) }}" alt="{{ $story->destination }}" 
                             class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                    </div>
                    
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload New Image (Optional)</label>
                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent" 
                           onchange="previewImage(event)">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image. Max: 5MB</p>
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    
                    <div class="mt-4">
                        <div id="imagePreview" class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center hidden">
                            <img id="previewImage" class="w-full h-full object-cover rounded-lg">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Status & Analytics -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Status & Analytics</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="is_active" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent">
                            <option value="1" {{ old('is_active', $story->is_active) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $story->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Views</label>
                        <input type="number" name="views" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent" 
                               min="0" value="{{ old('views', $story->views) }}">
                        @error('views')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Clicks</label>
                        <input type="number" name="clicks" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent" 
                               min="0" value="{{ old('clicks', $story->clicks) }}">
                        @error('clicks')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Performance Metrics -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Performance Metrics</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600">Click-through Rate</p>
                        <p class="text-xl font-bold text-blue-600">
                            {{ $story->views > 0 ? number_format(($story->clicks / $story->views) * 100, 2) : 0 }}%
                        </p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-gray-600">Engagement Score</p>
                        <p class="text-xl font-bold text-green-600">
                            {{ number_format(($story->views + $story->clicks) / 10) }}
                        </p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <p class="text-sm text-gray-600">Conversion Rate</p>
                        <p class="text-xl font-bold text-purple-600">
                            {{ $story->views > 0 ? number_format(($story->clicks / $story->views) * 100, 1) : 0 }}%
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Order -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Display Order</h3>
                <div class="max-w-xs">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                    <input type="number" name="order" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent" 
                           min="0" value="{{ old('order', $story->order) }}">
                    <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
                    @error('order')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex justify-between pt-6 border-t border-gray-200">
                <form action="{{ route('admin.stories.destroy', $story) }}" method="POST" class="inline"
                      onsubmit="return confirm('Are you sure you want to delete this story? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-xl font-bold transition-all">
                        <i class="fas fa-trash mr-2"></i> Delete
                    </button>
                </form>
                
                <div class="flex space-x-3">
                    <a href="{{ route('admin.stories.index') }}" class="bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-50 transition-all">
                        Cancel
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-forest to-forest-dark text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg transition-all">
                        <i class="fas fa-save mr-2"></i> Update Story
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const imagePreview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            imagePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        imagePreview.classList.add('hidden');
    }
}

// Form validation
document.getElementById('storyForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
    submitBtn.disabled = true;
});
</script>
@endsection
