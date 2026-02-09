@extends('admin.layouts.app')

@section('title', 'Destination Details: ' . $destination->name)

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-1 h-8 bg-forest rounded-full"></div>
                <h1 class="text-3xl font-black text-forest font-poppins uppercase tracking-tight">Geographic Intelligence</h1>
            </div>
            <p class="text-slate text-sm font-medium ml-4 italic">{{ $destination->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.destinations.edit', $destination) }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white text-forest font-black uppercase tracking-widest text-[10px] rounded-xl border border-forest/20 shadow-sm hover:shadow-md transition-all duration-300">
                <i class="fas fa-globe-americas text-gold"></i>
                Refine Territory
            </a>
            <a href="{{ route('admin.destinations.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-gray-50 text-slate font-bold rounded-xl border border-gray-200 hover:bg-gray-100 transition-all duration-300">
                <i class="fas fa-arrow-left text-sm"></i>
                Back to Atlas
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Visual Intelligence Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-[2.5rem] p-4 shadow-xl border border-gray-100 overflow-hidden group">
            <div class="relative w-full h-[400px] rounded-[2rem] overflow-hidden">
                @if($destination->image)
                    <img src="{{ asset('storage/' . $destination->image) }}" alt="{{ $destination->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                @else
                    <div class="w-full h-full bg-forest/5 flex items-center justify-center">
                        <i class="fas fa-mountain text-forest/20 text-6xl"></i>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-forest/60 via-transparent to-transparent"></div>
                <div class="absolute bottom-8 left-8">
                    <p class="text-[10px] font-black text-white/80 uppercase tracking-widest mb-1">Geographic Designation</p>
                    <h2 class="text-3xl font-black text-white font-poppins uppercase tracking-tighter">{{ $destination->name }}</h2>
                </div>
            </div>
            
            <div class="p-8">
                <div class="flex items-center gap-4 mb-8 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-gold text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate uppercase tracking-widest">Positioning</p>
                        <p class="text-sm font-bold text-forest">{{ $destination->location }}</p>
                    </div>
                </div>
                
                <h3 class="text-xs font-black text-forest uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-1 h-4 bg-gold rounded-full"></span>
                    Strategic Hallmarks
                </h3>
                <ul class="space-y-4">
                    @forelse($destination->highlights ?? [] as $highlight)
                        <li class="flex items-center gap-3 group">
                            <div class="w-8 h-8 rounded-lg bg-forest text-white flex items-center justify-center group-hover:bg-forest-light transition-colors shadow-sm">
                                <i class="fas fa-check text-[10px]"></i>
                            </div>
                            <span class="text-xs font-bold text-slate group-hover:text-forest transition-colors">{{ $highlight }}</span>
                        </li>
                    @empty
                        <li class="text-slate/40 text-xs italic text-center py-6 border border-dashed border-gray-200 rounded-2xl">No hallmark data synchronized.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Narrative Intelligence Card -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-[2.5rem] p-10 shadow-xl border border-gray-100 h-full relative overflow-hidden">
            <!-- Decorative Accent -->
            <div class="absolute top-10 right-10 w-24 h-24 bg-forest/5 rounded-full flex items-center justify-center">
                <i class="fas fa-feather-alt text-forest/20 text-3xl"></i>
            </div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-8 border-b border-gray-50 pb-6">
                    <i class="fas fa-university text-gold text-2xl"></i>
                    <h3 class="text-xl font-black text-forest font-poppins uppercase tracking-tight">Territorial Manuscript</h3>
                </div>
                
                <div class="bg-gray-50/50 p-8 rounded-[2rem] border border-gray-100 text-slate leading-loose font-medium italic underline-offset-8">
                    @if($destination->description)
                        {{ $destination->description }}
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-scroll-old text-4xl text-slate/10 mb-4 block"></i>
                            <p class="text-xs font-black uppercase tracking-widest text-slate/30">No descriptive manuscript has been established for this territory.</p>
                        </div>
                    @endif
                </div>
                
                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="p-6 bg-forest rounded-2xl shadow-lg shadow-forest/20">
                        <div class="flex items-center gap-4 text-white">
                            <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                                <i class="fas fa-suitcase-rolling text-gold"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-white/60 uppercase tracking-widest">Active Narratives</p>
                                <p class="text-2xl font-black font-poppins">0</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-lg">
                        <div class="flex items-center gap-4 text-forest">
                            <div class="w-12 h-12 bg-forest/5 rounded-xl flex items-center justify-center">
                                <i class="fas fa-chart-line text-gold"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate uppercase tracking-widest">Market Interest</p>
                                <p class="text-2xl font-black font-poppins">Optimal</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
