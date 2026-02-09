@extends('admin.layouts.app')

@section('title', 'User Intelligence: ' . $user->name)

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-1 h-8 bg-forest rounded-full"></div>
                <h1 class="text-3xl font-black text-forest font-poppins uppercase tracking-tight">Personnel Intelligence</h1>
            </div>
            <p class="text-slate text-sm font-medium ml-4 italic">{{ $user->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white text-forest font-black uppercase tracking-widest text-[10px] rounded-xl border border-forest/20 shadow-sm hover:shadow-md transition-all duration-300">
                <i class="fas fa-user-shield text-gold"></i>
                Adjust Clearance
            </a>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-gray-50 text-slate font-bold rounded-xl border border-gray-200 hover:bg-gray-100 transition-all duration-300">
                <i class="fas fa-arrow-left text-sm"></i>
                Back to Registry
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Personnel Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-gray-100 text-center relative overflow-hidden">
            <!-- Background Decorative Element -->
            <div class="absolute -top-10 -left-10 w-32 h-32 bg-forest/5 rounded-full"></div>
            
            <div class="relative mb-6">
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-forest to-forest-dark flex items-center justify-center mx-auto border-4 border-white shadow-2xl">
                    <span class="text-5xl font-black text-white font-poppins">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <!-- Status Ring (Placeholder) -->
                <div class="absolute bottom-0 right-1/2 translate-x-12 translate-y-1 w-8 h-8 rounded-full bg-white p-1 shadow-md">
                    <div class="w-full h-full rounded-full bg-green-500 border-2 border-white"></div>
                </div>
            </div>

            <h2 class="text-2xl font-black text-forest font-poppins uppercase tracking-tighter mb-1">{{ $user->name }}</h2>
            <p class="text-xs font-bold text-slate uppercase tracking-widest mb-6">{{ $user->email }}</p>
            
            <div class="flex justify-center mb-8">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full font-black uppercase tracking-widest text-[10px] shadow-sm bg-gray-100 text-slate">
                    <i class="fas fa-user"></i>
                    Customer
                </div>
            </div>
            
            <hr class="border-gray-50 mb-8">
            
            <div class="text-left space-y-6">
                <div class="flex items-center gap-4 group">
                    <div class="w-10 h-10 rounded-xl bg-forest/5 flex items-center justify-center group-hover:bg-forest/10 transition-colors">
                        <i class="fas fa-phone-alt text-forest text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate uppercase tracking-widest">Communication Portal</p>
                        <p class="text-xs font-bold text-forest">{{ $user->phone ?? 'Logistical Gap' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 group">
                    <div class="w-10 h-10 rounded-xl bg-forest/5 flex items-center justify-center group-hover:bg-forest/10 transition-colors">
                        <i class="fas fa-shield-alt text-gold text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate uppercase tracking-widest">Identity Authentication</p>
                        <p class="text-xs font-bold {{ $user->email_verified_at ? 'text-green-600' : 'text-red-500' }}">
                            {{ $user->email_verified_at ? 'Identity Verified' : 'Authentication Pending' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4 group">
                    <div class="w-10 h-10 rounded-xl bg-forest/5 flex items-center justify-center group-hover:bg-forest/10 transition-colors">
                        <i class="fas fa-calendar-check text-slate text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate uppercase tracking-widest">Registration Marker</p>
                        <p class="text-xs font-bold text-forest">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Personnel Analytics Card -->
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white rounded-[2.5rem] p-10 shadow-xl border border-gray-100 h-full">
            <div class="flex items-center gap-3 mb-10 border-b border-gray-50 pb-6">
                <i class="fas fa-chart-pie text-gold text-2xl"></i>
                <h3 class="text-xl font-black text-forest font-poppins uppercase tracking-tight">Active Engagement Pulse</h3>
            </div>
            
            
            <div class="space-y-6">
                <div class="flex items-center gap-3 mb-4">
                    <i class="fas fa-database text-gold"></i>
                    <h4 class="text-xs font-black text-forest font-poppins uppercase tracking-widest">Operational Metadata</h4>
                </div>
                <div class="bg-gray-900 p-8 rounded-[2rem] shadow-inner">
                    <pre class="font-mono text-[10px] text-green-400 leading-relaxed overflow-x-auto"><code>{{ json_encode($user->toArray(), JSON_PRETTY_PRINT) }}</code></pre>
                </div>
                <p class="text-[10px] font-bold text-slate/40 uppercase tracking-widest italic text-center">Encrypted personnel synchronization protocol active.</p>
            </div>
        </div>
    </div>
</div>
@endsection
