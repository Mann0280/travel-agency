@extends('admin.layouts.app')

@section('title', 'Package Details: ' . $package->name)

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-1 h-8 bg-forest rounded-full"></div>
                <h1 class="text-3xl font-black text-forest font-poppins uppercase tracking-tight">Package Intelligence</h1>
            </div>
            <p class="text-slate text-sm font-medium ml-4 italic">{{ $package->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.packages.edit', $package) }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white text-forest font-black uppercase tracking-widest text-[10px] rounded-xl border border-forest/20 shadow-sm hover:shadow-md transition-all duration-300">
                <i class="fas fa-edit text-gold"></i>
                Refine Modality
            </a>
            <a href="{{ route('admin.packages.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-gray-50 text-slate font-bold rounded-xl border border-gray-200 hover:bg-gray-100 transition-all duration-300">
                <i class="fas fa-arrow-left text-sm"></i>
                Back to Pipeline
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Primary Intelligence -->
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white rounded-[2rem] p-8 shadow-xl border border-gray-100 relative overflow-hidden">
            <!-- Decorative Gradient -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-forest/5 to-transparent rounded-full -mr-32 -mt-32"></div>
            
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-start gap-6 mb-8">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-forest/5 rounded-full mb-4">
                            <i class="fas fa-map-marker-alt text-gold text-xs"></i>
                            <span class="text-[10px] font-black text-forest uppercase tracking-widest">{{ $package->destination->name }}</span>
                        </div>
                        <h2 class="text-4xl font-black text-forest font-poppins leading-tight mb-2 uppercase tracking-tighter">{{ $package->name }}</h2>
                    </div>
                    <div class="bg-gradient-to-br from-forest to-forest-dark p-6 rounded-2xl shadow-xl text-center min-w-[180px]">
                        <p class="text-[10px] font-black text-white/60 uppercase tracking-widest mb-1">Investment Tier</p>
                        <p class="text-3xl font-black text-white font-poppins">₹{{ number_format($package->price, 0) }}</p>
                        <p class="text-[10px] text-gold font-bold">Per Experience</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 py-8 border-y border-gray-100 my-8">
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-black text-slate uppercase tracking-widest">Temporal Unit</span>
                        <span class="text-sm font-bold text-forest">{{ $package->duration }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-black text-slate uppercase tracking-widest">Fulfillment</span>
                        <span class="text-sm font-bold text-forest">{{ $package->agency->name }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-black text-slate uppercase tracking-widest">Operational</span>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            <span class="text-sm font-bold text-forest">Live</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-black text-slate uppercase tracking-widest">Premium Status</span>
                        <span class="inline-flex items-center gap-1.5 text-sm font-bold {{ $package->is_featured ? 'text-gold' : 'text-slate/40' }}">
                            <i class="fas {{ $package->is_featured ? 'fa-star' : 'fa-minus-circle' }}"></i>
                            {{ $package->is_featured ? 'Featured' : 'Standard' }}
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-quote-left text-2xl text-gold/30"></i>
                        <h3 class="text-lg font-black text-forest font-poppins uppercase tracking-tight">Narrative Manuscript</h3>
                    </div>
                    <div class="text-slate text-sm font-medium leading-relaxed bg-gray-50/50 p-6 rounded-2xl border border-gray-100 italic">
                        {{ $package->description }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Collateral Intelligence -->
    <div class="lg:col-span-1 space-y-8">
        <!-- Departure Hubs -->
        <div class="bg-forest rounded-[2rem] p-8 shadow-xl text-white">
            <div class="flex items-center gap-3 mb-6 border-b border-white/10 pb-4">
                <i class="fas fa-plane-departure text-gold"></i>
                <h3 class="text-sm font-black uppercase tracking-widest font-poppins">Strategic Departure Hubs</h3>
            </div>
            <ul class="space-y-4">
                @forelse($package->departure_cities ?? [] as $city)
                    <li class="flex items-center gap-3 p-3 bg-white/5 rounded-xl border border-white/5 hover:bg-white/10 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-gold/20 flex items-center justify-center">
                            <i class="fas fa-map-pin text-gold text-xs"></i>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider">{{ $city }}</span>
                    </li>
                @empty
                    <li class="text-white/40 italic text-xs text-center py-4 border border-dashed border-white/10 rounded-xl">No logistical hubs defined.</li>
                @endforelse
            </ul>
        </div>

        <!-- Metric Pulse -->
        <div class="bg-white rounded-[2rem] p-8 shadow-xl border border-gray-100">
            <h3 class="text-xs font-black text-forest uppercase tracking-widest mb-6 border-b border-gray-50 pb-4">Real-time Pulse Metrics</h3>
            <div class="space-y-6">
                <div class="flex justify-between items-center group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-forest/5 flex items-center justify-center group-hover:bg-forest/10 transition-colors">
                            <i class="fas fa-ticket-alt text-forest text-xs"></i>
                        </div>
                        <span class="text-[10px] font-black text-slate uppercase tracking-widest">Conversions</span>
                    </div>
                    <span class="text-sm font-black text-forest">0</span>
                </div>
                <div class="flex justify-between items-center group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-forest/5 flex items-center justify-center group-hover:bg-forest/10 transition-colors">
                            <i class="fas fa-star text-gold text-xs"></i>
                        </div>
                        <span class="text-[10px] font-black text-slate uppercase tracking-widest">Satisfaction Score</span>
                    </div>
                    <span class="text-sm font-black text-forest">0.0 / 5.0</span>
                </div>
                <div class="flex justify-between items-center group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-forest/5 flex items-center justify-center group-hover:bg-forest/10 transition-colors">
                            <i class="fas fa-history text-slate text-xs"></i>
                        </div>
                        <span class="text-[10px] font-black text-slate uppercase tracking-widest">Last Activity</span>
                    </div>
                    <span class="text-[10px] font-bold text-slate/40 italic">Never</span>
                </div>
            </div>
            
            <div class="mt-8 p-4 bg-gold/5 rounded-xl border border-gold/10">
                <div class="flex gap-3">
                    <i class="fas fa-info-circle text-gold text-sm mt-0.5"></i>
                    <p class="text-[10px] font-bold text-slate uppercase leading-normal">
                        Metrics are synchronized per booking cycle. Historical data unavailable for standard tiers.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
