@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header with Breadcrumb -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.stories.index') }}" class="text-gold hover:underline">Stories</a>
                <i class="fas fa-chevron-right mx-2"></i>
                <span>Add New Story</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Add New Story</h1>
            <p class="text-gray-600">Create a new upcoming travel story for homepage</p>
        </div>
        <div>
            <a href="{{ route('admin.stories.index') }}" class="bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-50 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>
    </div>
    
    <!-- Story Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.stories.store') }}" method="POST" enctype="multipart/form-data" id="storyForm" class="space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Destination Name *</label>
                        <input type="text" name="destination" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent" 
                               value="{{ old('destination') }}" required placeholder="e.g., Spiti Valley">
                        @error('destination')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                        <input type="date" name="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent" 
                               value="{{ old('date') }}" required>
                        <p class="text-xs text-gray-500 mt-1">Select the travel date</p>
                        @error('date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Associated Package</label>
                        <select name="package_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent">
                            <option value="">Select Package (Optional)</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
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
                                   {{ old('is_featured') ? 'checked' : '' }}>
                            <span class="text-gray-900 font-medium">Featured Story</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Image Upload -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Image</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Story Image *</label>
                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent" 
                           required onchange="previewImage(event)">
                    <p class="text-xs text-gray-500 mt-1">Upload an image. Recommended size: 400x400 pixels. Max: 5MB</p>
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    
                    <div class="mt-4">
                        <div id="imagePreview" class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center hidden">
                            <img id="previewImage" class="w-full h-full object-cover rounded-lg">
                        </div>
                        <div id="noImagePreview" class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                                <p class="text-xs text-gray-500 mt-1">Image Preview</p>
                            </div>
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
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Initial Views</label>
                        <input type="number" name="views" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent" 
                               min="0" value="{{ old('views', 0) }}">
                        @error('views')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Initial Clicks</label>
                        <input type="number" name="clicks" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent" 
                               min="0" value="{{ old('clicks', 0) }}">
                        @error('clicks')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Order -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Display Order</h3>
                <div class="max-w-xs">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                    <input type="number" name="order" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent" 
                           min="0" value="{{ old('order', 0) }}">
                    <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
                    @error('order')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.stories.index') }}" class="bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-50 transition-all">
                    Cancel
                </a>
                <button type="submit" class="bg-gradient-to-r from-forest to-forest-dark text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg transition-all">
                    <i class="fas fa-save mr-2"></i> Save Story
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const imagePreview = document.getElementById('imagePreview');
    const noImagePreview = document.getElementById('noImagePreview');
    const previewImage = document.getElementById('previewImage');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            imagePreview.classList.remove('hidden');
            noImagePreview.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        imagePreview.classList.add('hidden');
        noImagePreview.classList.remove('hidden');
    }
}

// Form validation
document.getElementById('storyForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
    submitBtn.disabled = true;
});
</script>
@endsection
