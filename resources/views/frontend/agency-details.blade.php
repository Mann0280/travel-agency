@extends('layouts.frontend')

@section('title', ($selectedPackage ? $selectedPackage->name . ' - ' . $selectedAgency->name : 'Package Details') . ' - ZUBEE')

@push('styles')
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
<style>
    /* Custom styles for mobile optimization */
    .package-detail-container {
        padding-bottom: 120px;
        scroll-behavior: smooth !important;
    }

    .tab-content {
        transition: opacity 0.3s ease;
    }

    .tab-content.hidden {
        display: none;
        opacity: 0;
    }

    .tab-content.active {
        display: block;
        opacity: 1;
    }

    /* Agency badge */
    .agency-badge {
        background: rgba(219, 179, 99, 0.2);
        border: 1px solid #dbb363;
        color: #dbb363;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 900;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Tabs Styling */
    .tabs-container {
        position: sticky;
        top: 80px; /* Below standard header */
        z-index: 30;
        background: #fdfbf7;
        margin-bottom: 24px;
        border-bottom: 1px solid rgba(168, 137, 77, 0.1);
    }

    .tabs-scroll-indicator {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 20;
        background: #fdfbf7;
        border: none;
        padding: 8px;
        cursor: pointer;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .tabs-scroll-indicator.left { left: 0; box-shadow: 5px 0 15px -5px rgba(0,0,0,0.1); }
    .tabs-scroll-indicator.right { right: 0; box-shadow: -5px 0 15px -5px rgba(0,0,0,0.1); }
    .tabs-scroll-indicator.hidden { opacity: 0; pointer-events: none; }

    .tabs-scroll-content {
        display: flex;
        overflow-x: auto;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        padding: 12px 16px;
        gap: 8px;
        scrollbar-width: none;
    }

    .tabs-scroll-content::-webkit-scrollbar { display: none; }

    .tab-button {
        flex-shrink: 0;
        padding: 10px 20px;
        font-size: 11px;
        font-weight: 800;
        border-radius: 25px;
        border: 1px solid rgba(168, 137, 77, 0.2);
        background: white;
        color: #57688a;
        transition: all 0.3s ease;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .tab-button.active {
        background: #17320B;
        color: #dbb363;
        border-color: #17320B;
        box-shadow: 0 4px 12px rgba(26, 58, 26, 0.2);
    }

    /* Timeline Stepper */
    .itinerary-step {
        position: relative;
        padding-left: 32px;
        padding-bottom: 32px;
    }
    .itinerary-step::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 24px;
        bottom: 0;
        width: 2px;
        background: rgba(219, 179, 99, 0.2);
    }
    .itinerary-step:last-child::before { display: none; }
    .itinerary-step::after {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 24px;
        height: 24px;
        background: white;
        border: 4px solid #dbb363;
        border-radius: 50%;
        z-index: 1;
    }
</style>
@endpush

@section('content')
<div class="bg-[#fdfbf7] min-h-screen">
    <!-- Header with back button -->
    <div class="sticky top-0 z-50 bg-forest p-4 flex items-center md:hidden">
        <a href="javascript:history.back()" class="mr-3 p-2 rounded-full bg-white/10">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-sm font-black uppercase tracking-widest text-white truncate">Package Details</h1>
    </div>

    <!-- Main Scrollable Content -->
    <div class="package-detail-container">
        <!-- Package Hero Section -->
        <div class="relative h-64 md:h-[400px]">
            <img src="{{ $selectedPackage->image ? asset('storage/' . $selectedPackage->image) : asset('images/placeholder.jpg') }}" 
                 alt="{{ $selectedPackage->name }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-forest via-forest/20 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-6 md:p-12">
                <div class="max-w-7xl mx-auto">
                    <div class="flex flex-col gap-2">
                        <span class="text-[10px] font-black uppercase tracking-[4px] text-gold/80">{{ $selectedPackage->destination->name }}</span>
                        <h2 class="text-3xl md:text-6xl font-black text-white uppercase tracking-tighter leading-none mb-2">
                            {{ $selectedPackage->name }}
                        </h2>
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="agency-badge">Crafted by: {{ $selectedAgency->name }}</div>
                            <div class="flex items-center gap-1 text-gold">
                                <i class="fas fa-star text-[10px]"></i>
                                <span class="text-[10px] font-black text-white">{{ number_format($selectedPackage->reviews()->avg('rating') ?: 4.8, 1) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Info Bar -->
        <div class="max-w-7xl mx-auto px-4 md:px-8 -mt-8 relative z-20">
            <div class="bg-white rounded-3xl border border-gold/10 shadow-elite p-6 grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="border-r border-gold/10 md:pr-6">
                    <span class="text-[8px] font-black uppercase tracking-widest text-gold block mb-1">Investment</span>
                    <div class="text-xl font-black text-forest tracking-tighter">₹{{ number_format($selectedPackage->price) }}</div>
                    <span class="text-[8px] font-bold text-slate uppercase">Per Guest</span>
                </div>
                <div class="border-r border-gold/10 md:pr-6 md:pl-6">
                    <span class="text-[8px] font-black uppercase tracking-widest text-gold block mb-1">Duration</span>
                    <div class="text-sm font-black text-forest uppercase tracking-tight">{{ $selectedPackage->duration }}</div>
                </div>
                <div class="border-r border-gold/10 md:pr-6 md:pl-6 hidden md:block">
                    <span class="text-[8px] font-black uppercase tracking-widest text-gold block mb-1">Travel Date</span>
                    <div class="text-sm font-black text-forest uppercase tracking-tight">{{ $selectedPackage->travel_date ? $selectedPackage->travel_date->format('M d, Y') : 'Flexible' }}</div>
                </div>
                <div class="md:pl-6">
                    <span class="text-[8px] font-black uppercase tracking-widest text-gold block mb-1">Verified Reviews</span>
                    <div class="text-sm font-black text-forest uppercase tracking-tight">{{ $selectedPackage->reviews()->count() }} Guest ratings</div>
                </div>
            </div>
        </div>

        <!-- Contact Actions -->
        @php
            $agencyPhone = $agencyContacts[$selectedAgency->name] ?? ($selectedAgency->phone ?: '9499658115');
            $whatsappMessage = urlencode("Hello, I'm interested in the " . $selectedPackage->name . " package with " . $selectedAgency->name . ". Please provide more details.");
        @endphp
        <div class="max-w-7xl mx-auto px-4 md:px-8 mt-12 grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="https://wa.me/91{{ $agencyPhone }}?text={{ $whatsappMessage }}" target="_blank"
               class="bg-[#25d366] text-white py-5 rounded-2xl flex items-center justify-center gap-3 font-black uppercase tracking-[3px] text-[10px] shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                <i class="fab fa-whatsapp text-lg"></i>
                Concierge WhatsApp
            </a>
            <a href="tel:+91{{ $agencyPhone }}"
               class="bg-forest text-white py-5 rounded-2xl flex items-center justify-center gap-3 font-black uppercase tracking-[3px] text-[10px] shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                <i class="fas fa-phone-alt text-lg"></i>
                Direct Connect Line
            </a>
        </div>

        <!-- Tab Navigation -->
        <div class="tabs-container mt-12">
            <div class="max-w-7xl mx-auto relative px-4 md:px-8">
                <button id="left-scroll" class="tabs-scroll-indicator left hidden">
                    <i class="fas fa-chevron-left text-gold text-xs"></i>
                </button>
                <div class="tabs-scroll-content scroll-hide" id="tabsScroll">
                    <button class="tab-button active" data-tab="itinerary">Itinerary Intelligence</button>
                    <button class="tab-button" data-tab="inclusion">Standard Inclusions</button>
                    <button class="tab-button" data-tab="exclusion">Exclusions Policy</button>
                    <button class="tab-button" data-tab="things">Voyage Essentials</button>
                    <button class="tab-button" data-tab="terms">Agreements & Terms</button>
                    <button class="tab-button" data-tab="contact">Partner Protocol</button>
                </div>
                <button id="right-scroll" class="tabs-scroll-indicator right">
                    <i class="fas fa-chevron-right text-gold text-xs"></i>
                </button>
            </div>
        </div>

        <!-- Tab Contents -->
        <div class="max-w-7xl mx-auto px-4 md:px-8 pb-24">
            <!-- Itinerary -->
            <div id="itinerary" class="tab-content active" data-aos="fade-up">
                <div class="space-y-4">
                    @foreach ($packageMetadata['itinerary'] as $day)
                        <div class="itinerary-step">
                            <h3 class="text-sm font-black text-forest uppercase tracking-tight mb-4">{{ $day['day'] }}</h3>
                            <div class="space-y-3">
                                @foreach ($day['activities'] as $activity)
                                    <div class="flex items-start gap-3 bg-white p-4 rounded-xl border border-gold/5 shadow-sm group hover:border-gold/30 transition-all">
                                        <i class="fas fa-chevron-right text-[8px] text-gold mt-1 group-hover:translate-x-1 transition-transform"></i>
                                        <span class="text-xs font-medium text-forest/70">{{ $activity }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Inclusions -->
            <div id="inclusion" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($packageMetadata['inclusions'] as $item)
                        <div class="flex items-center gap-4 bg-white p-5 rounded-[1.5rem] border border-gold/5 shadow-sm">
                            <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-500">
                                <i class="fas fa-check text-[10px]"></i>
                            </div>
                            <span class="text-xs font-bold text-forest uppercase tracking-tight">{{ $item }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Exclusions -->
            <div id="exclusion" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($packageMetadata['exclusions'] as $item)
                        <div class="flex items-center gap-4 bg-white p-5 rounded-[1.5rem] border border-gold/5 shadow-sm">
                            <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                                <i class="fas fa-times text-[10px]"></i>
                            </div>
                            <span class="text-xs font-bold text-forest uppercase tracking-tight">{{ $item }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Things to Carry -->
            <div id="things" class="tab-content hidden">
                <div class="bg-forest p-6 rounded-3xl mb-8 border-l-4 border-gold shadow-lg">
                    <p class="text-[10px] font-black text-gold uppercase tracking-[2px] leading-relaxed">
                        Note: During Monsoon pack luggage in plastic bags. For camping, carry bedding material as we provide tent & mattress only.
                    </p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($packageMetadata['things_to_carry'] as $item)
                        <div class="bg-white p-4 rounded-xl border border-gold/5 shadow-sm flex flex-col items-center text-center group hover:bg-forest transition-all duration-300">
                            <i class="fas fa-suitcase-rolling text-gold text-xs mb-2 group-hover:scale-110 transition-transform"></i>
                            <span class="text-[10px] font-black text-forest uppercase tracking-tight group-hover:text-white transition-colors">{{ $item }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Terms -->
            <div id="terms" class="tab-content hidden">
                <div class="space-y-4">
                    @foreach ($packageMetadata['terms_conditions'] as $term)
                        <div class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-gold/5 shadow-sm">
                            <i class="fas fa-info-circle text-gold text-[10px] mt-1"></i>
                            <p class="text-xs font-bold text-forest uppercase tracking-tight leading-relaxed">{{ $term }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Contact -->
            <div id="contact" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach ($packageMetadata['contact_info']['branches'] as $branch)
                        <div class="bg-white p-8 rounded-[2rem] border border-gold/5 shadow-elite">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-forest rounded-xl flex items-center justify-center text-gold">
                                    <i class="fas fa-building text-sm"></i>
                                </div>
                                <h4 class="text-lg font-black text-forest uppercase tracking-tight">{{ $branch['city'] }} Bureau</h4>
                            </div>
                            <p class="text-[10px] font-bold text-slate uppercase tracking-widest leading-loose mb-8">
                                {{ $branch['address'] }}
                            </p>
                            <div class="space-y-4">
                                @foreach ($branch['phones'] as $phone)
                                    <a href="tel:{{ $phone }}" class="flex items-center gap-4 text-forest hover:text-gold transition-colors group">
                                        <div class="w-8 h-8 rounded-full border border-gold/20 flex items-center justify-center group-hover:bg-gold transition-all">
                                            <i class="fas fa-phone-alt text-[8px] group-hover:text-forest"></i>
                                        </div>
                                        <span class="text-xs font-black tracking-widest">{{ $phone }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching logic
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const target = button.getAttribute('data-tab');
                
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                    content.classList.remove('active');
                });

                button.classList.add('active');
                const content = document.getElementById(target);
                content.classList.remove('hidden');
                setTimeout(() => content.classList.add('active'), 50);

                // Scroll tab into view if needed
                button.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            });
        });

        // Horizontal scroll logic
        const scrollContent = document.getElementById('tabsScroll');
        const leftBtn = document.getElementById('left-scroll');
        const rightBtn = document.getElementById('right-scroll');

        const updateButtons = () => {
            leftBtn.classList.toggle('hidden', scrollContent.scrollLeft <= 0);
            rightBtn.classList.toggle('hidden', scrollContent.scrollLeft + scrollContent.clientWidth >= scrollContent.scrollWidth);
        };

        scrollContent.addEventListener('scroll', updateButtons);
        leftBtn.addEventListener('click', () => scrollContent.scrollBy({ left: -200, behavior: 'smooth' }));
        rightBtn.addEventListener('click', () => scrollContent.scrollBy({ left: 200, behavior: 'smooth' }));

        window.addEventListener('resize', updateButtons);
        updateButtons();

        // Reveal animations
        AOS.init({
            duration: 800,
            once: true,
            offset: 50
        });
    });
</script>
@endpush
