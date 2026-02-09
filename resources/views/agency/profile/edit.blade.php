@extends('agency.layouts.app')

@section('title', 'Agency Profile')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-1 h-8 bg-forest rounded-full"></div>
                <h1 class="text-2xl font-black text-forest font-poppins">Agency Profile</h1>
            </div>
            <p class="text-slate text-sm font-medium ml-4">Manage your agency's public identity and branding</p>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto">
    <form action="{{ route('agency.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- Identity Card -->
        <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-lg">
            <div class="flex items-center gap-3 mb-8 border-b border-gray-100 pb-4">
                <i class="fas fa-id-card text-gold text-xl"></i>
                <h3 class="text-xl font-black text-forest font-poppins uppercase tracking-tight">Public Identity</h3>
            </div>

            <div class="flex flex-col md:flex-row gap-8 items-start">
                <!-- Logo Upload -->
                <div class="shrink-0">
                    <label class="block text-[10px] font-black text-slate uppercase tracking-widest mb-4 text-center">Agency Logo</label>
                    <div class="relative group">
                        <div class="w-32 h-32 rounded-3xl bg-forest/5 flex items-center justify-center border-2 border-dashed border-forest/20 overflow-hidden group-hover:border-forest/40 transition-all">
                            @if($agency->logo)
                                <img src="{{ asset('storage/' . $agency->logo) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-building text-3xl text-forest/20"></i>
                            @endif
                        </div>
                        <label for="logo" class="absolute -bottom-2 -right-2 w-10 h-10 bg-gold text-white rounded-xl shadow-lg flex items-center justify-center cursor-pointer hover:scale-110 transition-all">
                            <i class="fas fa-camera"></i>
                            <input type="file" name="logo" id="logo" class="hidden" accept="image/*">
                        </label>
                    </div>
                    @error('logo') <p class="text-red-500 text-[10px] mt-4 font-bold text-center">{{ $message }}</p> @enderror
                </div>

                <!-- Basic Details -->
                <div class="flex-1 space-y-6">
                    <div>
                        <label for="name" class="block text-xs font-black text-forest uppercase tracking-widest mb-2 font-poppins">Agency Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $agency->name) }}" 
                               class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:border-forest focus:ring-2 focus:ring-forest/10 outline-none transition-all font-medium text-forest" 
                               required>
                        @error('name') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-black text-forest uppercase tracking-widest mb-2 font-poppins">Experience Narrative (Description)</label>
                        <textarea name="description" id="description" rows="4" 
                                  class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:border-forest focus:ring-2 focus:ring-forest/10 outline-none transition-all font-medium text-forest leading-relaxed" 
                                  required>{{ old('description', $agency->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Parameters -->
        <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-lg">
            <div class="flex items-center gap-3 mb-8 border-b border-gray-100 pb-4">
                <i class="fas fa-map-marker-alt text-gold text-xl"></i>
                <h3 class="text-xl font-black text-forest font-poppins uppercase tracking-tight">Contact Parameters</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="email" class="block text-xs font-black text-forest uppercase tracking-widest mb-2 font-poppins">Official Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $agency->email) }}" 
                           class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:border-forest focus:ring-2 focus:ring-forest/10 outline-none transition-all font-medium text-forest" 
                           required>
                    @error('email') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="phone" class="block text-xs font-black text-forest uppercase tracking-widest mb-2 font-poppins">Direct Line (Phone)</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $agency->phone) }}" 
                           class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:border-forest focus:ring-2 focus:ring-forest/10 outline-none transition-all font-medium text-forest" 
                           required>
                    @error('phone') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-xs font-black text-forest uppercase tracking-widest mb-2 font-poppins">HQ Address</label>
                    <textarea name="address" id="address" rows="2" 
                              class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:border-forest focus:ring-2 focus:ring-forest/10 outline-none transition-all font-medium text-forest" 
                              required>{{ old('address', $agency->address) }}</textarea>
                    @error('address') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4 pb-12">
            <button type="submit" class="px-12 py-5 bg-gradient-to-r from-forest to-forest-dark text-white font-black uppercase tracking-widest text-xs rounded-2xl shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                Synchronize Agency Data
            </button>
        </div>
    </form>
</div>
@endsection
