@extends('layouts.frontend')

@section('title', $title . ' - ' . ($site_settings->get('site_name') ?? 'ZUBEEE'))

@section('content')
<div class="bg-champagne min-h-screen">
    <!-- Majestic Header -->
    <header class="pt-24 pb-12 px-8 max-w-7xl mx-auto text-center" data-aos="fade-down">
        <div class="flex justify-center items-center gap-4 mb-6">
            <span class="w-12 h-[2px] bg-gold"></span>
            <span class="text-[10px] font-black uppercase tracking-[5px] text-gold">Neural Knowledge</span>
            <span class="w-12 h-[2px] bg-gold"></span>
        </div>
        
        <h1 class="text-5xl md:text-7xl font-black text-forest uppercase tracking-tighter leading-none mb-4">
            {{ $title }}
        </h1>
        <p class="text-slate text-xs font-bold uppercase tracking-widest">
            Excellence in every detail of your journey
        </p>
    </header>

    <!-- Content curation -->
    <main class="max-w-4xl mx-auto px-8 pb-32">
        <div class="space-y-8">
            @forelse($contents as $index => $item)
                <div class="bg-white rounded-[2.5rem] border border-gold/5 shadow-elite p-10 hover:shadow-2xl transition-all duration-500" 
                     data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="flex items-start gap-6">
                        <div class="w-10 h-10 bg-forest rounded-full flex items-center justify-center text-gold font-black shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-forest uppercase tracking-tight mb-4">{{ $item->title }}</h3>
                            <div class="prose prose-forest max-w-none text-slate leading-relaxed">
                                {!! nl2br(e($item->content)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-32 bg-white rounded-[4rem] border border-gold/5 shadow-elite" data-aos="zoom-in">
                    <div class="w-20 h-20 bg-gold/5 rounded-full flex items-center justify-center mx-auto mb-8">
                        <i class="fas fa-file-alt text-2xl text-gold/30"></i>
                    </div>
                    <h3 class="text-2xl font-black text-forest uppercase tracking-tight mb-4">Coming Soon</h3>
                    <p class="text-slate text-xs font-bold uppercase tracking-widest max-w-md mx-auto leading-relaxed">
                        Our curators are currently preparing this section. Please check back shortly.
                    </p>
                </div>
            @endforelse
        </div>

        <div class="mt-20 text-center">
            <a href="{{ route('home') }}" class="inline-block px-10 py-5 bg-forest text-white font-black uppercase tracking-widest text-[10px] rounded-full hover:bg-gold transition-all shadow-elite">
                Return to Port
            </a>
        </div>
    </main>
</div>
@endsection
