@extends('admin.layouts.app')

@section('title', 'Agency Profile - ' . $agency->name)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            @if($agency->logo)
                <img src="{{ asset('storage/' . $agency->logo) }}" class="w-20 h-20 object-cover rounded-3xl border-4 border-white shadow-xl">
            @else
                <div class="w-20 h-20 bg-forest text-white font-black text-3xl rounded-3xl flex items-center justify-center shadow-xl uppercase">
                    {{ substr($agency->name, 0, 1) }}
                </div>
            @endif
            <div>
                <h1 class="text-3xl font-bold text-forest font-poppins">{{ $agency->name }}</h1>
                <div class="flex items-center gap-3 mt-1">
                    <span class="px-3 py-1 bg-forest/5 text-forest text-[10px] font-black uppercase tracking-widest rounded-full border border-forest/10">
                        {{ $agency->status }}
                    </span>
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-[10px] {{ $i <= $agency->rating ? 'text-gold' : 'text-gray-200' }}"></i>
                        @endfor
                        <span class="text-xs font-bold text-gray-500 ml-1">{{ number_format($agency->rating, 1) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.agencies.impersonate', $agency) }}" class="px-6 py-2 bg-gold/10 text-gold font-bold rounded-xl hover:bg-gold/20 transition-all">
                <i class="fas fa-user-secret mr-2"></i> Login as Agency
            </a>
            <a href="{{ route('admin.agencies.edit', $agency) }}" class="px-6 py-2 bg-forest text-white font-bold rounded-xl hover:bg-forest-dark transition-all">
                <i class="fas fa-edit mr-2"></i> Edit Profile
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="md:col-span-2 space-y-6">
            <!-- About -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
                <h3 class="text-lg font-black text-forest font-poppins uppercase tracking-tight mb-4 border-l-4 border-gold pl-4">About Agency</h3>
                <p class="text-gray-600 leading-relaxed">{{ $agency->description }}</p>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mt-8 pt-8 border-t border-gray-50">
                    <div>
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Experience</div>
                        <div class="text-base font-bold text-forest">{{ $agency->experience_years }} Years</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Packages</div>
                        <div class="text-base font-bold text-forest">{{ $agency->packages_count }} Published</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Commission</div>
                        <div class="text-base font-bold text-forest">{{ $agency->commission_percentage }}%</div>
                    </div>
                </div>
            </div>

            <!-- Documents/Stats -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-5 bg-gray-50 border-b border-gray-100 italic">
                    <h3 class="text-base font-bold text-forest">Business Credentials</h3>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div>
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">GST Number</div>
                                <div class="text-sm font-bold text-gray-700">{{ $agency->gst_number ?? 'Not Provided' }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div>
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">PAN Number</div>
                                <div class="text-sm font-bold text-gray-700">{{ $agency->pan_number ?? 'Not Provided' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-university"></i>
                            </div>
                            <div>
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Bank Account</div>
                                <div class="text-sm font-bold text-gray-700">{{ $agency->bank_name ?? 'Bank' }} ({{ substr($agency->account_number, -4) ?? '****' }})</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div>
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">License</div>
                                <div class="text-sm font-bold text-gray-700">{{ $agency->license_number ?? 'Not Provided' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Contact Card -->
            <div class="bg-forest rounded-3xl shadow-xl p-8 text-white relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-10 text-9xl">
                    <i class="fas fa-address-book"></i>
                </div>
                <h3 class="text-lg font-bold mb-6 font-poppins border-b border-white/10 pb-4">Contact Info</h3>
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <i class="fas fa-user mt-1 text-gold"></i>
                        <div>
                            <div class="text-[10px] font-black text-white/40 uppercase tracking-widest">Contact Person</div>
                            <div class="text-sm font-bold">{{ $agency->contact_person }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <i class="fas fa-envelope mt-1 text-gold"></i>
                        <div>
                            <div class="text-[10px] font-black text-white/40 uppercase tracking-widest">Email Address</div>
                            <div class="text-sm font-bold">{{ $agency->email }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <i class="fas fa-phone mt-1 text-gold"></i>
                        <div>
                            <div class="text-[10px] font-black text-white/40 uppercase tracking-widest">Primary Phone</div>
                            <div class="text-sm font-bold">{{ $agency->phone }}</div>
                        </div>
                    </div>
                    @if($agency->alternate_phone)
                    <div class="flex items-start gap-4">
                        <i class="fas fa-mobile-alt mt-1 text-gold"></i>
                        <div>
                            <div class="text-[10px] font-black text-white/40 uppercase tracking-widest">Alt. Phone</div>
                            <div class="text-sm font-bold">{{ $agency->alternate_phone }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Address Card -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
                <h3 class="text-sm font-black text-forest font-poppins uppercase tracking-widest mb-4">Location</h3>
                <div class="flex items-start gap-3">
                    <i class="fas fa-map-marker-alt text-forest mt-1"></i>
                    <div class="text-sm text-gray-600 leading-relaxed font-medium">
                        {{ $agency->address_line1 }}<br>
                        @if($agency->address_line2) {{ $agency->address_line2 }}<br> @endif
                        {{ $agency->city }}, {{ $agency->state }}<br>
                        {{ $agency->country }} - {{ $agency->pincode }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
