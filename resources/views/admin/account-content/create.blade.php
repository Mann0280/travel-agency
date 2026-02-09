@extends('admin.layouts.app')

@section('title', 'Add Content')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-1 h-8 bg-forest rounded-full"></div>
                <h1 class="text-3xl font-black text-forest font-poppins">Synthesize New Content</h1>
            </div>
            <p class="text-slate text-sm font-medium ml-4">Establish new informational blocks for the digital portal</p>
        </div>
        <a href="{{ route('admin.account-content.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-gray-50 text-slate font-bold rounded-xl border border-gray-200 hover:bg-gray-100 transition-all duration-300">
            <i class="fas fa-arrow-left text-sm"></i>
            Back to Library
        </a>
    </div>
</div>

<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.account-content.store') }}" method="POST" class="space-y-8">
        @csrf
        
        <!-- Content Identity Card -->
        <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-lg">
            <div class="flex items-center gap-3 mb-8 border-b border-gray-100 pb-4">
                <i class="fas fa-file-signature text-gold text-xl"></i>
                <h3 class="text-xl font-black text-forest font-poppins uppercase tracking-tight">Content Identity</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="type" class="block text-xs font-black text-forest uppercase tracking-widest mb-2 font-poppins">Conceptual Type</label>
                    <select name="type" id="type" 
                            class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:border-forest focus:ring-2 focus:ring-forest/10 outline-none transition-all font-medium text-forest appearance-none" 
                            required>
                        <option value="faq">FAQ (Frequently Asked)</option>
                        <option value="about">About Us Section</option>
                        <option value="policy">Policy / Terms</option>
                    </select>
                </div>
                
                <div>
                    <label for="title" class="block text-xs font-black text-forest uppercase tracking-widest mb-2 font-poppins">Primary Designation (Title)</label>
                    <input type="text" name="title" id="title" 
                           class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:border-forest focus:ring-2 focus:ring-forest/10 outline-none transition-all font-medium text-forest" 
                           required placeholder="e.g. What defines our heritage?">
                </div>
            </div>
            
            <div class="mt-8">
                <label for="content" class="block text-xs font-black text-forest uppercase tracking-widest mb-2 font-poppins">Informational Manuscript (Content)</label>
                <textarea name="content" id="content" rows="8" 
                          class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:border-forest focus:ring-2 focus:ring-forest/10 outline-none transition-all font-medium text-forest leading-relaxed" 
                          required placeholder="Detail the informational narrative here..."></textarea>
            </div>
        </div>
        
        <!-- Parameters Card -->
        <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-lg">
            <div class="flex items-center gap-3 mb-8 border-b border-gray-100 pb-4">
                <i class="fas fa-sliders-h text-gold text-xl"></i>
                <h3 class="text-xl font-black text-forest font-poppins uppercase tracking-tight">Structural Parameters</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="sort_order" class="block text-xs font-black text-forest uppercase tracking-widest mb-2 font-poppins">Structural Sequence (Sort)</label>
                    <input type="number" name="sort_order" id="sort_order" 
                           class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:border-forest focus:ring-2 focus:ring-forest/10 outline-none transition-all font-medium text-forest" 
                           value="0">
                </div>
                
                <div class="flex items-center justify-between p-6 bg-forest/5 rounded-2xl border border-forest/10">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center">
                            <i class="fas fa-satellite text-gold text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-forest font-poppins uppercase tracking-tight">Broadcast</h4>
                            <p class="text-[10px] text-slate font-medium italic">Active Deployment</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1" class="sr-only peer" checked>
                        <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-forest"></div>
                    </label>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end pt-4 pb-12">
            <button type="submit" class="px-12 py-5 bg-gradient-to-r from-forest to-forest-dark text-white font-black uppercase tracking-widest text-xs rounded-2xl shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                Synchronize Information Token
            </button>
        </div>
    </form>
</div>
@endsection
