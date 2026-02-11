@extends('agency.layouts.app')

@section('title', 'Agency Profile')
@section('page_title', 'Agency Profile')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-1 h-8 bg-forest rounded-full"></div>
            <h1 class="text-2xl font-black text-forest font-poppins tracking-tight">Public Identity</h1>
        </div>
        <p class="text-slate text-sm font-medium ml-4">Manage how your agency appears to travelers around the world.</p>
    </div>

    <form action="{{ route('agency.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Identity & Logo -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Logo Card -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-forest/5 rounded-bl-full -mr-4 -mt-4 transition-all group-hover:bg-forest/10"></div>
                    
                    <h3 class="text-lg font-bold text-forest font-poppins mb-6 relative">Brand Logo</h3>
                    
                    <div class="flex flex-col items-center">
                        <div class="relative group/upload cursor-pointer">
                            <div class="w-40 h-40 rounded-full bg-slate-50 border-4 border-white shadow-xl flex items-center justify-center overflow-hidden transition-all duration-300 group-hover/upload:border-gold/50 group-hover/upload:shadow-2xl">
                                @if($agency->logo)
                                    <img id="logoPreview" src="{{ asset('storage/' . $agency->logo) }}" class="w-full h-full object-cover">
                                @else
                                    <div id="placeholderLogo" class="flex flex-col items-center text-slate-300">
                                        <i class="fas fa-building text-4xl mb-2"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-widest">No Logo</span>
                                    </div>
                                    <img id="logoPreview" class="hidden w-full h-full object-cover">
                                @endif
                                
                                <!-- Overlay -->
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover/upload:opacity-100 transition-opacity duration-300">
                                    <i class="fas fa-camera text-white text-2xl"></i>
                                </div>
                            </div>
                            
                            <input type="file" name="logo" id="logo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" onchange="previewImage(this)">
                        </div>
                        
                        <div class="mt-4 text-center">
                            <p class="text-xs font-bold text-slate uppercase tracking-wide">Tap to Change</p>
                            <p class="text-[10px] text-slate-400 mt-1">Recommended: 500x500px (PNG/JPG)</p>
                        </div>
                        @error('logo') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Agency Quick Stats (Decorative) -->
                <div class="bg-gradient-to-br from-forest to-forest-dark rounded-2xl p-6 shadow-lg text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gold uppercase tracking-widest mb-1">Account Status</p>
                        <h3 class="text-xl font-bold font-poppins flex items-center gap-2">
                             Verified Partner <i class="fas fa-check-circle text-gold"></i>
                        </h3>
                        <div class="mt-6 pt-6 border-t border-white/10 grid grid-cols-2 gap-4 text-center">
                            <div>
                                <span class="block text-xl font-black text-white">{{ $agency->packages->count() }}</span>
                                <span class="text-[10px] text-white/60 uppercase tracking-wider">Packages</span>
                            </div>
                            <div>
                                <span class="block text-xl font-black text-white">{{ $agency->created_at->format('Y') }}</span>
                                <span class="text-[10px] text-white/60 uppercase tracking-wider">Member Since</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Decor -->
                    <i class="fas fa-certificate absolute -bottom-4 -right-4 text-8xl text-white/5 rotate-12"></i>
                </div>
            </div>

            <!-- Right Column: Details Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info Card -->
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="w-10 h-10 rounded-lg bg-gold/10 flex items-center justify-center text-gold">
                            <i class="fas fa-info-circle text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-forest font-poppins">Basic Information</h3>
                            <p class="text-xs text-slate">Your primary agency details</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Agency Name -->
                        <div class="group">
                            <label class="block text-xs font-bold text-slate uppercase tracking-widest mb-2 group-focus-within:text-forest transition-colors">
                                Agency Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-store text-slate-400 group-focus-within:text-forest transition-colors"></i>
                                </div>
                                <input type="text" name="name" value="{{ old('name', $agency->name) }}" 
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-gray-200 rounded-xl focus:bg-white focus:border-forest focus:ring-4 focus:ring-forest/5 outline-none transition-all font-semibold text-forest placeholder-slate-300"
                                    placeholder="e.g. Dream Travels">
                            </div>
                            @error('name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <!-- Description -->
                        <div class="group">
                            <label class="block text-xs font-bold text-slate uppercase tracking-widest mb-2 group-focus-within:text-forest transition-colors">
                                About Us (Description) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <textarea name="description" rows="4" 
                                    class="w-full p-4 bg-slate-50 border border-gray-200 rounded-xl focus:bg-white focus:border-forest focus:ring-4 focus:ring-forest/5 outline-none transition-all font-medium text-forest placeholder-slate-300 leading-relaxed resize-none"
                                    placeholder="Tell travelers about your agency...">{{ old('description', $agency->description) }}</textarea>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1 text-right">Brief narrative about your services/history.</p>
                            @error('description') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Info Card -->
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                     <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-600">
                            <i class="fas fa-address-book text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-forest font-poppins">Contact Details</h3>
                            <p class="text-xs text-slate">How customers can reach you</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div class="group">
                            <label class="block text-xs font-bold text-slate uppercase tracking-widest mb-2 group-focus-within:text-forest transition-colors">
                                Official Email <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-slate-400 group-focus-within:text-forest transition-colors"></i>
                                </div>
                                <input type="email" name="email" value="{{ old('email', $agency->email) }}" 
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-gray-200 rounded-xl focus:bg-white focus:border-forest focus:ring-4 focus:ring-forest/5 outline-none transition-all font-semibold text-forest placeholder-slate-300"
                                    placeholder="contact@agency.com">
                            </div>
                            @error('email') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <!-- Phone -->
                        <div class="group">
                            <label class="block text-xs font-bold text-slate uppercase tracking-widest mb-2 group-focus-within:text-forest transition-colors">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-slate-400 group-focus-within:text-forest transition-colors"></i>
                                </div>
                                <input type="text" name="phone" value="{{ old('phone', $agency->phone) }}" 
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-gray-200 rounded-xl focus:bg-white focus:border-forest focus:ring-4 focus:ring-forest/5 outline-none transition-all font-semibold text-forest placeholder-slate-300"
                                    placeholder="+91 98765 43210">
                            </div>
                            @error('phone') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <!-- Address -->
                        <div class="group md:col-span-2">
                            <label class="block text-xs font-bold text-slate uppercase tracking-widest mb-2 group-focus-within:text-forest transition-colors">
                                HQ Address <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute top-3 left-4 flex items-start pointer-events-none">
                                    <i class="fas fa-map-marker-alt text-slate-400 group-focus-within:text-forest transition-colors mt-1"></i>
                                </div>
                                <textarea name="address" rows="2" 
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-gray-200 rounded-xl focus:bg-white focus:border-forest focus:ring-4 focus:ring-forest/5 outline-none transition-all font-semibold text-forest placeholder-slate-300 resize-none"
                                    placeholder="Full office address">{{ old('address', $agency->address) }}</textarea>
                            </div>
                            @error('address') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="flex justify-end pt-4">
                    <button type="submit" class="w-full md:w-auto px-8 py-4 bg-gradient-to-r from-gold to-yellow-600 text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 hover:from-yellow-600 hover:to-yellow-700 transition-all duration-300 flex items-center justify-center gap-3">
                        <i class="fas fa-save text-base"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                const preview = document.getElementById('logoPreview');
                const placeholder = document.getElementById('placeholderLogo');
                
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if(placeholder) placeholder.classList.add('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
