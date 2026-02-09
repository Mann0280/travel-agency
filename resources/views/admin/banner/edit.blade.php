@extends('admin.layouts.app')

@section('title', 'Edit Banner: ' . $banner->title)

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-1 h-8 bg-forest rounded-full"></div>
                <h1 class="text-3xl font-black text-forest font-poppins uppercase tracking-tight">Refine Hero Visual</h1>
            </div>
            <p class="text-slate text-sm font-medium ml-4 italic">{{ $banner->title }}</p>
        </div>
        <a href="{{ route('admin.banner.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-gray-50 text-slate font-bold rounded-xl border border-gray-200 hover:bg-gray-100 transition-all duration-300">
            <i class="fas fa-arrow-left text-sm"></i>
            Back to Showcase
        </a>
    </div>
</div>

<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.banner.update', $banner) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- Banner Definition Card -->
        <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-lg">
            <div class="flex items-center gap-3 mb-8 border-b border-gray-100 pb-4">
                <i class="fas fa-magic text-gold text-xl"></i>
                <h3 class="text-xl font-black text-forest font-poppins uppercase tracking-tight">Visual Narrative Refinement</h3>
            </div>

            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-xs font-black text-forest uppercase tracking-widest mb-2 font-poppins">Refined Headline</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $banner->title) }}" 
                           class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:border-forest focus:ring-2 focus:ring-forest/10 outline-none transition-all font-medium text-forest" 
                           required>
                    @error('title') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-xs font-black text-forest uppercase tracking-widest mb-2 font-poppins">Refined Narrative (Optional)</label>
                    <textarea name="description" id="description" rows="3" 
                              class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:border-forest focus:ring-2 focus:ring-forest/10 outline-none transition-all font-medium text-forest leading-relaxed">{{ old('description', $banner->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                    <div class="md:col-span-1">
                        <label class="block text-xs font-black text-forest uppercase tracking-widest mb-2 font-poppins">Legacy Canvas</label>
                        <div class="relative w-full h-32 rounded-xl overflow-hidden border border-gray-100 shadow-sm">
                            <img src="{{ asset('storage/' . $banner->image) }}" alt="Current Image" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label for="image" class="block text-xs font-black text-forest uppercase tracking-widest mb-2 font-poppins">Update Hero Canvas</label>
                        <input type="file" name="image" id="image" 
                               class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 border-dashed rounded-xl focus:border-forest outline-none transition-all font-medium text-slate cursor-pointer">
                        <div class="mt-2 text-[10px] text-slate font-bold uppercase tracking-widest">
                            <i class="fas fa-info-circle text-gold"></i>
                            Leave blank to preserve legacy canvas • Max 2MB
                        </div>
                    </div>
                    @error('image') <div class="col-span-full"><p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p></div> @enderror
                </div>
            </div>
        </div>

        <!-- Logistics Card -->
        <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-lg">
            <div class="flex items-center gap-3 mb-8 border-b border-gray-100 pb-4">
                <i class="fas fa-route text-gold text-xl"></i>
                <h3 class="text-xl font-black text-forest font-poppins uppercase tracking-tight">Navigation & Sequencing</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="link" class="block text-xs font-black text-forest uppercase tracking-widest mb-2 font-poppins">CTA Destination (URL)</label>
                    <input type="url" name="link" id="link" value="{{ old('link', $banner->link) }}" 
                           class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:border-forest focus:ring-2 focus:ring-forest/10 outline-none transition-all font-medium text-forest">
                    @error('link') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="sort_order" class="block text-xs font-black text-forest uppercase tracking-widest mb-2 font-poppins">Carousel Modality (Sort)</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" 
                           class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:border-forest focus:ring-2 focus:ring-forest/10 outline-none transition-all font-medium text-forest">
                    @error('sort_order') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 flex items-center justify-between p-6 bg-forest/5 rounded-2xl border border-forest/10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center">
                        <i class="fas fa-broadcast-tower text-gold text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-forest font-poppins uppercase tracking-tight">Active Broadcast</h4>
                        <p class="text-xs text-slate font-medium">Toggle the visibility of this hero visual</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                    <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-forest"></div>
                </label>
            </div>
        </div>

        <div class="flex justify-end pt-4 pb-12">
            <button type="submit" class="px-12 py-5 bg-gradient-to-r from-forest to-forest-dark text-white font-black uppercase tracking-widest text-xs rounded-2xl shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                Synchronize Hero Modalities
            </button>
        </div>
    </form>
</div>
@endsection
