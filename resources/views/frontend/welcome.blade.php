@extends('layouts.frontend')

@section('title', ($site_settings->get('site_name') ?? 'ZUBEEE') . ' - Curated Luxury Voyages')

@section('content')
<div class="relative min-h-screen">
    <!-- Hero Section -->
    <section class="relative h-[90vh] flex items-center justify-center overflow-hidden">
        <!-- Background Image with Parallax-like effect -->
        <div class="absolute inset-0 z-0 scale-110">
            <img src="https://images.unsplash.com/photo-1544413647-15242313f54a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                 class="w-full h-full object-cover opacity-80" alt="Luxury Travel">
            <div class="absolute inset-0 bg-gradient-to-b from-forest/40 via-transparent to-champagne"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 text-center px-4 max-w-5xl mx-auto" data-aos="fade-up">
            <div class="flex items-center justify-center gap-4 mb-8">
                <span class="w-12 h-[1px] bg-gold"></span>
                <span class="text-[10px] font-black uppercase tracking-[5px] text-gold">Elite Travel Concierge</span>
                <span class="w-12 h-[1px] bg-gold"></span>
            </div>
            
            <h1 class="text-6xl md:text-8xl font-serif text-white uppercase leading-[0.9] tracking-tighter mb-8">
                UNVEIL THE <br>
                <span class="text-gold italic font-normal tracking-normal lowercase">extraordinary</span>
            </h1>

            <p class="text-white/80 text-lg md:text-xl font-light tracking-wide max-w-2xl mx-auto mb-12">
                Bespoke voyages curated for the discerning traveler. <br> Discover private retreats and hidden horizons.
            </p>

            <!-- Search Entrance -->
            <div class="bg-white/10 backdrop-blur-xl p-2 rounded-full border border-white/20 max-w-3xl mx-auto shadow-2xl">
                <form action="{{ route('search') }}" method="GET" class="flex flex-col md:flex-row items-center gap-2">
                    <div class="flex-1 w-full relative">
                        <i class="fas fa-search absolute left-6 top-1/2 -translate-y-1/2 text-gold"></i>
                        <input type="text" name="destination" placeholder="Where shall we take you?" 
                               class="w-full bg-white/5 border-none rounded-full py-4 pl-14 pr-6 text-white placeholder:text-white/40 focus:ring-1 focus:ring-gold transition-all text-sm uppercase tracking-widest font-black">
                    </div>
                    <button type="submit" class="w-full md:w-auto bg-gold text-forest font-black uppercase tracking-[2px] text-[10px] px-10 py-4 rounded-full hover:bg-white transition-all transform active:scale-95 shadow-xl">
                        Explore Collections
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex flex-col items-center gap-4 animate-bounce opacity-40">
            <span class="text-[10px] uppercase tracking-[4px] font-black text-forest">Scroll</span>
            <div class="w-[1px] h-12 bg-forest"></div>
        </div>
    </section>

    <!-- Curated Highlights -->
    <section class="py-24 px-8 max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
            <div data-aos="fade-right">
                <span class="text-[10px] font-black uppercase tracking-[4px] text-gold mb-4 block">Seasonal Edit</span>
                <h2 class="text-4xl md:text-5xl font-black text-forest uppercase tracking-tighter">Current <br> <span class="text-gold">Collections</span></h2>
            </div>
            <div class="max-w-md text-right border-r-2 border-gold/20 pr-8" data-aos="fade-left">
                <p class="text-slate text-sm font-medium leading-relaxed italic">
                    "Travel is the only thing you buy that makes you richer. We ensure the investment is as flawless as the destination."
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            @php
                $collections = [
                    ['name' => 'Amalfi Coast', 'tagline' => 'La Dolce Vita', 'image' => 'https://images.unsplash.com/photo-1533105079780-92b9be482077?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'],
                    ['name' => 'Kyoto Zen', 'tagline' => 'Timeless Heritage', 'image' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'],
                    ['name' => 'Bali Retreat', 'tagline' => 'Tropical Luxury', 'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'],
                ];
            @endphp

            @foreach($collections as $item)
            <div class="group cursor-pointer" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="relative aspect-[4/5] overflow-hidden rounded-[2rem] mb-6">
                    <img src="{{ $item['image'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $item['name'] }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-forest/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>
                <span class="text-[9px] font-black uppercase tracking-[3px] text-gold block mb-1">{{ $item['tagline'] }}</span>
                <h3 class="text-xl font-black text-forest uppercase tracking-tight group-hover:text-gold transition-colors">{{ $item['name'] }}</h3>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Boutique CTA -->
    <section class="bg-forest py-24 px-8 overflow-hidden relative">
        <!-- Abstract background elements -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-gold/10 rounded-full -mr-48 -mt-48 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-gold/5 rounded-full -ml-48 -mb-48 blur-3xl"></div>

        <div class="max-w-4xl mx-auto text-center relative z-10" data-aos="zoom-in">
            <h2 class="text-4xl md:text-6xl font-serif text-white uppercase leading-tight mb-8 italic">
                Ready to begin your <br> <span class="not-italic font-black text-gold">Masterpiece?</span>
            </h2>
            <p class="text-white/60 font-light mb-12 max-w-xl mx-auto leading-relaxed">
                Connect with our travel artisans to design a journey that transcends the ordinary. Your private escape is just a conversation away.
            </p>
            <div class="flex flex-col md:flex-row justify-center gap-6">
                <a href="{{ route('search') }}" class="px-12 py-5 bg-gold text-forest font-black uppercase tracking-widest text-[11px] rounded-full hover:bg-white transition-all shadow-2xl">
                    View All Collections
                </a>
                <a href="#" class="px-12 py-5 border border-white/20 text-white font-black uppercase tracking-widest text-[11px] rounded-full hover:bg-white/10 transition-all">
                    Private Concierge
                </a>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
    [data-aos] {
        pointer-events: none;
    }
    .aos-animate {
        pointer-events: auto;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    });
</script>
@endpush
