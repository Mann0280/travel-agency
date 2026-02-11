@extends('agency.layouts.app')

@section('title', 'Agency Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center gap-3 mb-3">
        <div class="w-1 h-8 bg-forest rounded-full"></div>
        <h1 class="text-3xl font-black text-forest font-poppins">Dashboard Overview</h1>
    </div>
    <p class="text-slate text-sm font-medium ml-4">Welcome back, {{ $agency->name }}! Here's your performance overview.</p>
</div>

<!-- Primary Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8 mb-8">
    <!-- Total Packages Card -->
    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 group hover:border-forest/20">
        <div class="flex items-start justify-between mb-4">
            <div class="w-16 h-16 bg-gradient-to-br from-forest to-forest-dark rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-suitcase-rolling text-white text-2xl"></i>
            </div>
            <div class="bg-green-50 px-3 py-1 rounded-full">
                <span class="text-xs font-bold text-green-600">Active</span>
            </div>
        </div>
        <div>
            <p class="text-sm font-semibold text-slate mb-1 font-poppins">My Packages</p>
            <h3 class="text-4xl font-black text-forest font-poppins">{{ $stats['total_packages'] }}</h3>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
            <a href="{{ route('agency.packages.index') }}" class="text-sm font-bold text-forest hover:text-forest-dark transition-colors flex items-center gap-2 group/link">
                Manage Packages
                <i class="fas fa-arrow-right text-xs group-hover/link:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>

    <!-- Revenue Card -->
    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 group hover:border-gold/30">
        <div class="flex items-start justify-between mb-4">
            <div class="w-16 h-16 bg-gradient-to-br from-gold to-yellow-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-dollar-sign text-white text-2xl"></i>
            </div>
            <div class="bg-yellow-50 px-3 py-1 rounded-full">
                <span class="text-xs font-bold text-yellow-600">Revenue</span>
            </div>
        </div>
        <div>
            <p class="text-sm font-semibold text-slate mb-1 font-poppins">Total Earnings</p>
            <h3 class="text-4xl font-black text-forest font-poppins">₹{{ number_format($stats['total_revenue']) }}</h3>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
            <span class="text-sm font-bold text-gold flex items-center gap-2">
                Confirmed Bookings Only
            </span>
        </div>
    </div>
</div>

<!-- Click Stats Grid (Mini) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- WhatsApp Clicks -->
    <div class="bg-white rounded-xl p-4 shadow-md border border-gray-100 flex items-center gap-4 hover:shadow-lg transition-all">
        <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600">
            <i class="fab fa-whatsapp text-xl"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate uppercase tracking-wider">WhatsApp Leads</p>
            <h4 class="text-xl font-black text-forest">{{ $stats['whatsapp_clicks'] }}</h4>
        </div>
    </div>

    <!-- Call Clicks -->
    <div class="bg-white rounded-xl p-4 shadow-md border border-gray-100 flex items-center gap-4 hover:shadow-lg transition-all">
        <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
            <i class="fas fa-phone-alt text-xl"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate uppercase tracking-wider">Call Leads</p>
            <h4 class="text-xl font-black text-forest">{{ $stats['call_clicks'] }}</h4>
        </div>
    </div>

    <!-- Total Interactions -->
    <div class="bg-white rounded-xl p-4 shadow-md border border-gray-100 flex items-center gap-4 hover:shadow-lg transition-all">
        <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600">
            <i class="fas fa-mouse-pointer text-xl"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate uppercase tracking-wider">Total Interactions</p>
            <h4 class="text-xl font-black text-forest">{{ $stats['total_clicks'] }}</h4>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Revenue Chart -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-lg">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h3 class="text-xl font-black text-forest font-poppins mb-1">Revenue Overview</h3>
                    <p class="text-sm text-slate font-medium">Monthly revenue performance for {{ date('Y') }}</p>
                </div>
                <div class="text-right">
                     <span class="text-xs font-semibold text-slate block mb-1">Total Year Revenue</span>
                     <p class="text-2xl font-black text-forest font-poppins">₹{{ number_format($stats['total_revenue']) }}</p>
                </div>
            </div>
            
            <div class="h-72 relative">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="space-y-6">
        <div class="bg-gradient-to-br from-forest to-forest-dark p-8 rounded-2xl shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gold/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full blur-2xl"></div>
            
            <h3 class="text-xl font-black text-white font-poppins mb-6 relative z-10">Quick Actions</h3>
            
            <div class="grid grid-cols-2 gap-4 relative z-10">
                <a href="{{ route('agency.packages.create') }}" class="flex flex-col items-center justify-center p-5 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl hover:bg-gold hover:border-gold transition-all duration-300 group">
                    <i class="fas fa-plus text-gold group-hover:text-forest text-2xl mb-3"></i>
                    <span class="text-xs font-bold text-white group-hover:text-forest text-center">Add Package</span>
                </a>
                
                <a href="{{ route('agency.packages.index') }}" class="flex flex-col items-center justify-center p-5 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl hover:bg-gold hover:border-gold transition-all duration-300 group">
                    <i class="fas fa-list text-gold group-hover:text-forest text-2xl mb-3"></i>
                    <span class="text-xs font-bold text-white group-hover:text-forest text-center">My Packages</span>
                </a>
                
                <a href="{{ route('agency.analytics.clicks') }}" class="flex flex-col items-center justify-center p-5 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl hover:bg-gold hover:border-gold transition-all duration-300 group">
                    <i class="fas fa-chart-bar text-gold group-hover:text-forest text-2xl mb-3"></i>
                    <span class="text-xs font-bold text-white group-hover:text-forest text-center">Analytics</span>
                </a>
                
                <a href="{{ route('agency.profile.edit') }}" class="flex flex-col items-center justify-center p-5 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl hover:bg-gold hover:border-gold transition-all duration-300 group">
                    <i class="fas fa-user-edit text-gold group-hover:text-forest text-2xl mb-3"></i>
                    <span class="text-xs font-bold text-white group-hover:text-forest text-center">Edit Profile</span>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart');
        if (ctx) {
            const context = ctx.getContext('2d');
            
            // Create Gradient
            const gradient = context.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, '#1a3a1a'); // Forest green
            gradient.addColorStop(1, 'rgba(58, 109, 44, 0.1)');

            const chartData = @json($chartData);

            new Chart(context, {
                type: 'bar',
                data: {
                    labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
                    datasets: [{
                        label: 'Revenue',
                        data: chartData,
                        backgroundColor: gradient,
                        borderRadius: 6,
                        borderWidth: 0,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return '₹' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: 'rgba(58, 109, 44, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                font: { 
                                    size: 11, 
                                    weight: '600', 
                                    family: 'Poppins' 
                                },
                                color: '#57688a',
                                callback: function(value) { 
                                    if(value >= 1000) return '₹' + (value / 1000) + 'k';
                                    return '₹' + value;
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { 
                                    size: 11, 
                                    weight: '700', 
                                    family: 'Poppins' 
                                },
                                color: '#57688a'
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
