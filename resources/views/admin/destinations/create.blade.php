@extends('admin.layouts.app')

@section('title', 'Add New Destination')

@section('content')
<div class="space-y-8 animate-fade-in pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-gray-100 pb-8">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-forest transition-colors">Dashboard</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <a href="{{ route('admin.destinations.index') }}" class="hover:text-forest transition-colors">Destinations</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-gray-900 font-medium">Add New</span>
            </div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight font-poppins">Add New Destination</h1>
            <p class="text-gray-500 mt-1">Design a breathtaking new location for your travelers</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.destinations.index') }}" class="px-6 py-2.5 bg-white text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all font-bold text-sm">
                Cancel
            </a>
            <button type="submit" form="destinationForm" class="btn btn-primary shadow-lg shadow-gold/20 hover:shadow-gold/40 transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-save mr-2"></i>
                <span>Create Destination</span>
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-2xl mb-8 animate-shake">
            <div class="flex items-center gap-3 text-red-800 mb-2">
                <i class="fas fa-exclamation-triangle"></i>
                <strong class="font-black text-sm uppercase tracking-wider">Validation Errors</strong>
            </div>
            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="destinationForm" action="{{ route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        
        <!-- Left Column: Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Basic Information -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-forest/10 text-forest rounded-xl flex items-center justify-center">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-gray-900 font-poppins">Basic Information</h2>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-widest mt-0.5">Core Destination Details</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-500 uppercase tracking-widest ml-1">Destination Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g., Spiti Valley"
                                   class="w-full px-5 py-3.5 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-gold focus:ring-4 focus:ring-gold/10 transition-all duration-300 font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-500 uppercase tracking-widest ml-1">Location <span class="text-red-500">*</span></label>
                            <input type="text" name="location" required value="{{ old('location') }}" placeholder="e.g., Himachal Pradesh, India"
                                   class="w-full px-5 py-3.5 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-gold focus:ring-4 focus:ring-gold/10 transition-all duration-300 font-medium">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest ml-1">Detailed Description <span class="text-red-500">*</span></label>
                        <textarea name="description" required rows="5" placeholder="Captivate travelers with a beautiful description..."
                                  class="w-full px-5 py-3.5 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-gold focus:ring-4 focus:ring-gold/10 transition-all duration-300 font-medium resize-none">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-50">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-500 uppercase tracking-widest ml-1">Category</label>
                            <select name="category" class="w-full px-5 py-3.5 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-gold focus:ring-4 focus:ring-gold/10 transition-all duration-300 font-medium appearance-none cursor-pointer">
                                <option value="">Select Category</option>
                                @php
                                    $categories = explode(',', \App\Models\Setting::get('package_categories', 'adventure, hill-station, cultural, beach, desert, trekking, nature, heritage, religious'));
                                @endphp
                                @foreach($categories as $cat)
                                    @php $cat = trim($cat); @endphp
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-500 uppercase tracking-widest ml-1">Best Time to Visit</label>
                            <input type="text" name="best_time" value="{{ old('best_time') }}" placeholder="e.g., April to October"
                                   class="w-full px-5 py-3.5 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-gold focus:ring-4 focus:ring-gold/10 transition-all duration-300 font-medium">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest ml-1">Search Tags</label>
                        <input type="text" name="tags" value="{{ old('tags') }}" placeholder="mountains, adventure, cold desert"
                               class="w-full px-5 py-3.5 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-gold focus:ring-4 focus:ring-gold/10 transition-all duration-300 font-medium">
                        <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wide px-1">Separate with commas for best search results</p>
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-search"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-gray-900 font-poppins">Search Engine Optimization</h2>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-widest mt-0.5">Control how this appears on Google</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest ml-1">SEO Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Concise title for search results"
                               class="w-full px-5 py-3.5 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-gold focus:ring-4 focus:ring-gold/10 transition-all duration-300 font-medium">
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest ml-1">SEO Meta Description</label>
                        <textarea name="meta_description" rows="3" placeholder="Engaging summary for search result snippets..."
                                  class="w-full px-5 py-3.5 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-gold focus:ring-4 focus:ring-gold/10 transition-all duration-300 font-medium resize-none text-sm">{{ old('meta_description') }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest ml-1">SEO Focus Keywords</label>
                        <input type="text" name="seo_keywords" value="{{ old('seo_keywords') }}" placeholder="e.g., travel to spiti, best places in himachal"
                               class="w-full px-5 py-3.5 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-gold focus:ring-4 focus:ring-gold/10 transition-all duration-300 font-medium">
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Sidebar -->
        <div class="space-y-8">
            <!-- Featured Image -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 bg-gold/10 text-gold rounded-lg flex items-center justify-center text-sm">
                        <i class="fas fa-image"></i>
                    </div>
                    <h3 class="font-black text-gray-900 font-poppins text-sm uppercase tracking-wider">Featured Image</h3>
                </div>

                <div class="relative group">
                    <div class="file-upload-area relative aspect-video rounded-2xl border-2 border-dashed border-gray-100 bg-gray-50 flex flex-col items-center justify-center p-6 text-center cursor-pointer hover:border-gold hover:bg-gold/5 transition-all duration-500 overflow-hidden" 
                         onclick="document.getElementById('imageInput').click()">
                        
                        <div id="uploadPlaceholder" class="transition-all duration-500">
                            <div class="w-12 h-12 bg-white shadow-sm rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 group-hover:scale-110 group-hover:rotate-12 transition-all">
                                <i class="fas fa-cloud-upload-alt text-xl"></i>
                            </div>
                            <p class="text-xs font-black text-gray-900 uppercase tracking-widest mb-1">Click to Upload</p>
                            <p class="text-[10px] text-gray-400 font-medium">PNG, JPG up to 5MB</p>
                        </div>

                        <div id="imagePreview" class="absolute inset-0 hidden border-4 border-white shadow-inner">
                            <img src="" alt="Preview" class="w-full h-full object-cover rounded-xl">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="bg-white text-gray-900 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">Change Photo</span>
                            </div>
                        </div>

                        <input type="file" id="imageInput" name="image" accept="image/*" class="hidden">
                    </div>

                    <button type="button" onclick="clearImage()" id="removeImageBtn" class="absolute -top-3 -right-3 w-8 h-8 bg-red-500 text-white rounded-full shadow-lg items-center justify-center hidden hover:scale-110 transition-all z-10">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                @error('image')
                    <p class="text-red-500 text-[10px] font-bold mt-3 px-1 uppercase tracking-wider">{{ $message }}</p>
                @enderror
            </div>

            <!-- Publishing Status -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 bg-green-50 text-green-600 rounded-lg flex items-center justify-center text-sm">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3 class="font-black text-gray-900 font-poppins text-sm uppercase tracking-wider">Publishing</h3>
                </div>

                <div class="space-y-4">
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex items-start gap-4 cursor-pointer hover:border-gold transition-all" onclick="document.getElementById('statusCheckbox').click()">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="status" id="statusCheckbox" value="1" checked class="w-5 h-5 rounded-md border-2 border-gray-200 text-forest focus:ring-forest transition-all">
                        </div>
                        <div class="flex-1">
                            <label for="statusCheckbox" class="block text-xs font-black text-gray-900 uppercase tracking-wider cursor-pointer">Live Status</label>
                            <p class="text-[10px] text-gray-500 mt-1 font-medium leading-relaxed">Instantly visible in search and package management.</p>
                        </div>
                    </div>

                    <div class="px-2 pt-2">
                        <div class="flex items-center gap-2 text-forest text-[10px] font-black uppercase tracking-widest">
                            <i class="fas fa-check-circle"></i>
                            <span>Ready to save</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Tips -->
            <div class="bg-gradient-to-br from-forest to-forest-dark rounded-3xl p-8 text-white shadow-xl shadow-forest/20">
                <i class="fas fa-lightbulb text-gold text-2xl mb-4"></i>
                <h4 class="font-black text-lg font-poppins tracking-tight mb-2 leading-tight">Pro Tip: Make it Visual!</h4>
                <p class="text-white/70 text-xs leading-relaxed">Destinations with high-quality landscape photos see 40% more engagement on linked packages. Aim for 1920x1080 resolution.</p>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('uploadPlaceholder');
        const removeBtn = document.getElementById('removeImageBtn');

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.querySelector('img').src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    placeholder.classList.add('opacity-0', 'scale-90');
                    removeBtn.classList.remove('hidden');
                    removeBtn.classList.add('flex');
                };
                reader.readAsDataURL(file);
            }
        });

        window.clearImage = function() {
            imageInput.value = '';
            imagePreview.classList.add('hidden');
            placeholder.classList.remove('opacity-0', 'scale-90');
            removeBtn.classList.add('hidden');
            removeBtn.classList.remove('flex');
        };
    });
</script>
@endpush
@endsection
