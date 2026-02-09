@extends('admin.layouts.app')

@section('title', 'Edit Destination')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-forest font-poppins">Edit Destination</h1>
            <p class="text-slate mt-1">Update destination details</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.destinations.index') }}" class="btn-secondary">
                <i class="fas fa-times"></i>
                <span>Cancel</span>
            </a>
            <button type="submit" form="destinationForm" class="btn-primary">
                <i class="fas fa-save"></i>
                <span>Update Destination</span>
            </button>
        </div>
    </div>

    <!-- Form -->
    <form id="destinationForm" action="{{ route('admin.destinations.update', $destination) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Error Messages -->
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mt-2 ml-4 list-disc">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information Card -->
                <div class="card">
                    <h2 class="text-xl font-bold text-forest mb-6 font-poppins flex items-center gap-2">
                        <i class="fas fa-info-circle text-gold"></i>
                        Basic Information
                    </h2>
                    
                    <div class="space-y-5">
                        <!-- Destination Name -->
                        <div>
                            <label class="form-label">
                                Destination Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   required
                                   placeholder="e.g., Spiti Valley"
                                   class="form-input @error('name') border-red-500 @enderror"
                                   value="{{ old('name', $destination->name) }}">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label class="form-label">
                                Location <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="location" 
                                   required
                                   placeholder="e.g., Himachal Pradesh, India"
                                   class="form-input @error('location') border-red-500 @enderror"
                                   value="{{ old('location', $destination->location) }}">
                            @error('location')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="form-label">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                name="description"
                                rows="5"
                                required
                                placeholder="Describe the destination..."
                                class="form-textarea @error('description') border-red-500 @enderror">{{ old('description', $destination->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category & Best Time -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select @error('category') border-red-500 @enderror">
                                    <option value="">Select Category</option>
                                    <option value="Adventure" {{ old('category', $destination->category) == 'Adventure' ? 'selected' : '' }}>Adventure</option>
                                    <option value="Hill Station" {{ old('category', $destination->category) == 'Hill Station' ? 'selected' : '' }}>Hill Station</option>
                                    <option value="Cultural" {{ old('category', $destination->category) == 'Cultural' ? 'selected' : '' }}>Cultural</option>
                                    <option value="Desert" {{ old('category', $destination->category) == 'Desert' ? 'selected' : '' }}>Desert</option>
                                    <option value="Beach" {{ old('category', $destination->category) == 'Beach' ? 'selected' : '' }}>Beach</option>
                                    <option value="Trekking" {{ old('category', $destination->category) == 'Trekking' ? 'selected' : '' }}>Trekking</option>
                                    <option value="Heritage" {{ old('category', $destination->category) == 'Heritage' ? 'selected' : '' }}>Heritage</option>
                                    <option value="Nature" {{ old('category', $destination->category) == 'Nature' ? 'selected' : '' }}>Nature</option>
                                    <option value="Religious" {{ old('category', $destination->category) == 'Religious' ? 'selected' : '' }}>Religious</option>
                                </select>
                                @error('category')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Best Time to Visit</label>
                                <input type="text" 
                                       name="best_time"
                                       placeholder="e.g., April to October"
                                       class="form-input @error('best_time') border-red-500 @enderror"
                                       value="{{ old('best_time', $destination->best_time) }}">
                                @error('best_time')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Tags -->
                        <div>
                            <label class="form-label">Tags</label>
                            <input type="text" 
                                   name="tags"
                                   placeholder="mountains, adventure, cold desert"
                                   class="form-input @error('tags') border-red-500 @enderror"
                                   value="{{ old('tags', is_array($destination->tags) ? implode(', ', $destination->tags) : '') }}">
                            <p class="text-sm text-slate mt-2">
                                <i class="fas fa-info-circle text-gold mr-1"></i>
                                Separate tags with commas. These help in search.
                            </p>
                            @error('tags')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEO Information Card -->
                <div class="card">
                    <h2 class="text-xl font-bold text-forest mb-6 font-poppins flex items-center gap-2">
                        <i class="fas fa-search text-gold"></i>
                        SEO Information
                    </h2>
                    
                    <div class="space-y-5">
                        <!-- Meta Title -->
                        <div>
                            <label class="form-label">Meta Title</label>
                            <input type="text" 
                                   name="meta_title"
                                   placeholder="Optimized title for search engines"
                                   class="form-input @error('meta_title') border-red-500 @enderror"
                                   value="{{ old('meta_title', $destination->meta_title) }}">
                            @error('meta_title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label class="form-label">Meta Description</label>
                            <textarea 
                                name="meta_description"
                                rows="3"
                                placeholder="Brief description for search results"
                                class="form-textarea @error('meta_description') border-red-500 @enderror">{{ old('meta_description', $destination->meta_description) }}</textarea>
                            @error('meta_description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SEO Keywords -->
                        <div>
                            <label class="form-label">SEO Keywords</label>
                            <input type="text" 
                                   name="seo_keywords"
                                   placeholder="spiti valley, himachal, adventure, mountains"
                                   class="form-input @error('seo_keywords') border-red-500 @enderror"
                                   value="{{ old('seo_keywords', $destination->seo_keywords) }}">
                            @error('seo_keywords')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Featured Image Card -->
                <div class="card">
                    <h2 class="text-xl font-bold text-forest mb-6 font-poppins flex items-center gap-2">
                        <i class="fas fa-image text-gold"></i>
                        Featured Image
                    </h2>
                    
                    @if($destination->image)
                        <div id="currentImage" class="mb-4">
                            <img src="{{ asset('storage/' . $destination->image) }}" 
                                 alt="{{ $destination->name }}" 
                                 class="w-full h-48 object-cover rounded-xl">
                            <p class="text-sm text-slate mt-2 text-center">Current Image</p>
                        </div>
                    @endif
                    
                    <div class="file-upload-area border-2 border-dashed border-forest/30 rounded-xl p-8 text-center cursor-pointer hover:border-forest hover:bg-forest/5 transition-all duration-300" 
                         onclick="document.getElementById('imageInput').click()">
                        <i class="fas fa-cloud-upload-alt text-5xl text-forest/30 mb-4"></i>
                        <p class="text-forest font-semibold mb-2">{{ $destination->image ? 'Replace Image' : 'Click to upload or drag and drop' }}</p>
                        <p class="text-sm text-slate">PNG, JPG, GIF up to 5MB</p>
                        <input type="file" 
                               id="imageInput"
                               name="image"
                               accept="image/*"
                               class="hidden">
                    </div>
                    
                    <div id="imagePreview" class="mt-4 hidden">
                        <img src="" alt="Preview" class="w-full h-48 object-cover rounded-xl">
                        <button type="button" 
                                onclick="clearImage()" 
                                class="mt-3 w-full px-4 py-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all font-semibold text-sm">
                            <i class="fas fa-times mr-1"></i>Remove New Image
                        </button>
                    </div>
                    
                    @error('image')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Card -->
                <div class="card">
                    <h2 class="text-xl font-bold text-forest mb-6 font-poppins flex items-center gap-2">
                        <i class="fas fa-toggle-on text-gold"></i>
                        Status
                    </h2>
                    
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" 
                               name="status"
                               {{ old('status', $destination->status) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-2 border-forest/30 text-forest focus:ring-forest focus:ring-offset-0 cursor-pointer">
                        <div>
                            <span class="text-forest font-semibold group-hover:text-gold transition-colors">Active Destination</span>
                            <p class="text-sm text-slate">Make this destination visible to users</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Image upload preview
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const currentImage = document.getElementById('currentImage');
    const uploadArea = document.querySelector('.file-upload-area');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.querySelector('img').src = e.target.result;
                imagePreview.classList.remove('hidden');
                if (currentImage) currentImage.style.opacity = '0.5';
            };
            reader.readAsDataURL(file);
        }
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('border-forest', 'bg-forest/10');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('border-forest', 'bg-forest/10');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('border-forest', 'bg-forest/10');
        
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            imageInput.files = dataTransfer.files;
            imageInput.dispatchEvent(new Event('change'));
        }
    });

    function clearImage() {
        imageInput.value = '';
        imagePreview.classList.add('hidden');
        if (currentImage) currentImage.style.opacity = '1';
    }
</script>
@endpush
@endsection
