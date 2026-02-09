@extends('admin.layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center gap-3 mb-3">
        <div class="w-1 h-8 bg-forest rounded-full"></div>
        <h1 class="text-3xl font-black text-forest font-poppins">Dashboard Overview</h1>
    </div>
    <p class="text-slate text-sm font-medium ml-4">Welcome back! Here's what's happening with your travel agency today.</p>
</div>

<!-- Primary Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <!-- Total Packages Card -->
    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 group hover:border-forest/20">
        <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-forest to-forest-dark rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-box-open text-white text-xl"></i>
            </div>
            <div class="bg-green-50 px-3 py-1 rounded-full">
                <span class="text-xs font-bold text-green-600">Active</span>
            </div>
        </div>
        <div>
            <p class="text-sm font-semibold text-slate mb-1 font-poppins">Total Packages</p>
            <h3 class="text-3xl font-black text-forest font-poppins">{{ $stats['total_packages'] }}</h3>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.packages.index') }}" class="text-xs font-bold text-forest hover:text-forest-dark transition-colors flex items-center gap-2 group/link">
                View All Packages
                <i class="fas fa-arrow-right text-xs group-hover/link:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>

    <!-- Travel Agencies Card -->
    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 group hover:border-gold/30">
        <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-gold to-yellow-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-building text-white text-xl"></i>
            </div>
            <div class="bg-yellow-50 px-3 py-1 rounded-full">
                <span class="text-xs font-bold text-yellow-600">Partners</span>
            </div>
        </div>
        <div>
            <p class="text-sm font-semibold text-slate mb-1 font-poppins">Travel Agencies</p>
            <h3 class="text-3xl font-black text-forest font-poppins">{{ $stats['total_agencies'] }}</h3>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.agencies.index') }}" class="text-xs font-bold text-gold hover:text-yellow-700 transition-colors flex items-center gap-2 group/link">
                View All Agencies
                <i class="fas fa-arrow-right text-xs group-hover/link:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>

    <!-- Total Bookings Card -->
    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 group hover:border-blue-200">
        <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-calendar-check text-white text-xl"></i>
            </div>
            <div class="bg-blue-50 px-3 py-1 rounded-full">
                <span class="text-xs font-bold text-blue-600">Confirmed</span>
            </div>
        </div>
        <div>
            <p class="text-sm font-semibold text-slate mb-1 font-poppins">Total Bookings</p>
            <h3 class="text-3xl font-black text-forest font-poppins">{{ $stats['total_bookings'] }}</h3>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.bookings.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors flex items-center gap-2 group/link">
                View All Bookings
                <i class="fas fa-arrow-right text-xs group-hover/link:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>

    <!-- Registered Users Card -->
    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 group hover:border-purple-200">
        <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
            <div class="bg-purple-50 px-3 py-1 rounded-full">
                <span class="text-xs font-bold text-purple-600">Members</span>
            </div>
        </div>
        <div>
            <p class="text-sm font-semibold text-slate mb-1 font-poppins">Registered Users</p>
            <h3 class="text-3xl font-black text-forest font-poppins">{{ number_format($stats['total_users']) }}</h3>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-purple-600 hover:text-purple-700 transition-colors flex items-center gap-2 group/link">
                View All Users
                <i class="fas fa-arrow-right text-xs group-hover/link:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>

    <!-- Feedback Card -->
    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 group hover:border-orange-200">
        <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-comment-dots text-white text-xl"></i>
            </div>
            @php $newFeedbackCount = \App\Models\Feedback::where('status', 'new')->count(); @endphp
            @if($newFeedbackCount > 0)
                <div class="bg-orange-50 px-3 py-1 rounded-full animate-bounce">
                    <span class="text-xs font-bold text-orange-600">{{ $newFeedbackCount }} New</span>
                </div>
            @endif
        </div>
        <div>
            <p class="text-sm font-semibold text-slate mb-1 font-poppins">Feedback</p>
            <h3 class="text-3xl font-black text-forest font-poppins">{{ number_format($stats['total_feedback']) }}</h3>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.feedback.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-700 transition-colors flex items-center gap-2 group/link">
                View All Feedback
                <i class="fas fa-arrow-right text-xs group-hover/link:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Revenue Overview Chart -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-lg">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h3 class="text-xl font-black text-forest font-poppins mb-1">Revenue Overview</h3>
                    <p class="text-sm text-slate font-medium">Monthly revenue performance</p>
                </div>
                <div class="flex gap-6">
                    <div class="text-right">
                        <span class="text-xs font-semibold text-slate block mb-1">This Month</span>
                        <p class="text-2xl font-black text-forest font-poppins">₹2,45,680</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold text-slate block mb-1">Last Month</span>
                        <p class="text-2xl font-black text-gray-400 font-poppins">₹1,98,450</p>
                    </div>
                </div>
            </div>
            
            <!-- Chart Canvas -->
            <div class="h-72 relative">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="space-y-6">
        <!-- Quick Actions Card -->
        <div class="bg-gradient-to-br from-forest to-forest-dark p-8 rounded-2xl shadow-xl relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-gold/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full blur-2xl"></div>
            
            <h3 class="text-xl font-black text-white font-poppins mb-6 relative z-10">Quick Actions</h3>
            
            <div class="grid grid-cols-2 gap-4 relative z-10">
                <a href="{{ route('admin.packages.create') }}" class="flex flex-col items-center justify-center p-5 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl hover:bg-gold hover:border-gold transition-all duration-300 group">
                    <i class="fas fa-plus text-gold group-hover:text-forest text-2xl mb-3"></i>
                    <span class="text-xs font-bold text-white group-hover:text-forest text-center">Add Package</span>
                </a>
                
                <a href="{{ route('admin.agencies.create') }}" class="flex flex-col items-center justify-center p-5 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl hover:bg-gold hover:border-gold transition-all duration-300 group">
                    <i class="fas fa-building text-gold group-hover:text-forest text-2xl mb-3"></i>
                    <span class="text-xs font-bold text-white group-hover:text-forest text-center">Add Agency</span>
                </a>
                
                <a href="{{ route('admin.users.create') }}" class="flex flex-col items-center justify-center p-5 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl hover:bg-gold hover:border-gold transition-all duration-300 group">
                    <i class="fas fa-user-plus text-gold group-hover:text-forest text-2xl mb-3"></i>
                    <span class="text-xs font-bold text-white group-hover:text-forest text-center">Add User</span>
                </a>
                
                <a href="{{ route('admin.settings.index') }}" class="flex flex-col items-center justify-center p-5 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl hover:bg-gold hover:border-gold transition-all duration-300 group">
                    <i class="fas fa-cog text-gold group-hover:text-forest text-2xl mb-3"></i>
                    <span class="text-xs font-bold text-white group-hover:text-forest text-center">Settings</span>
                </a>
            </div>
        </div>

        <!-- System Status Card -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-lg">
            <h3 class="text-lg font-black text-forest font-poppins mb-4">System Status</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-slate">Server Status</span>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-bold text-green-600">Online</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-slate">Database</span>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-bold text-green-600">Connected</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-slate">Storage</span>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                        <span class="text-xs font-bold text-yellow-600">75% Used</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Two Column Layout: Recent Bookings & Recent Feedback -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
    <!-- Recent Bookings Section -->
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-lg">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black text-forest font-poppins mb-1">Recent Bookings</h3>
                <p class="text-sm text-slate font-medium">Latest customer reservations</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="text-sm font-bold text-forest hover:text-forest-dark transition-colors">
                View All 
            </a>
        </div>
        
        <div class="divide-y divide-gray-100">
            @forelse($stats['recent_bookings'] as $booking)
            <div class="px-8 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors group">
                <div class="flex items-center gap-4 flex-1">
                    <div class="w-10 h-10 bg-forest/10 rounded-lg flex items-center justify-center text-forest font-bold text-sm">
                        {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-forest font-poppins">{{ $booking->user->name ?? 'Guest User' }}</h4>
                        <p class="text-xs text-slate font-medium truncate max-w-[150px]">{{ $booking->package->title ?? 'Package' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold text-forest block">₹{{ number_format($booking->package->price ?? 0) }}</span>
                    <span class="text-[10px] text-gray-400">{{ $booking->created_at->format('M d') }}</span>
                </div>
            </div>
            @empty
            <div class="px-8 py-10 text-center">
                <p class="text-sm text-gray-400">No recent bookings</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Feedback Section -->
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-lg">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black text-forest font-poppins mb-1">Customer Feedback</h3>
                <p class="text-sm text-slate font-medium">What travelers are saying</p>
            </div>
            <a href="{{ route('admin.feedback.index') }}" class="text-sm font-bold text-orange-600 hover:text-orange-700 transition-colors">
                View All
            </a>
        </div>
        
        <div class="divide-y divide-gray-100">
            @forelse($stats['recent_feedbacks'] as $feedback)
            <div class="px-8 py-4 flex flex-col hover:bg-gray-50 transition-colors group">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold text-xs border border-orange-200">
                            {{ strtoupper(substr($feedback->user->name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-forest font-poppins">{{ $feedback->user->name ?? 'Anonymous' }}</h4>
                            <div class="flex items-center gap-1 text-gold text-[10px]">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $feedback->rating ? '' : 'text-gray-200' }}"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($feedback->status == 'new')
                            <span class="w-2 h-2 bg-orange-500 rounded-full" title="New Feedback"></span>
                        @endif
                        <span class="text-[10px] text-gray-400">{{ $feedback->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <p class="text-xs text-slate line-clamp-2 italic">"{{ $feedback->message }}"</p>
            </div>
            @empty
            <div class="px-8 py-10 text-center">
                <p class="text-sm text-gray-400">No feedback yet</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Create Gradient
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, '#3a6d2c'); // Forest green
        gradient.addColorStop(1, 'rgba(58, 109, 44, 0.1)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
                datasets: [{
                    label: 'Revenue',
                    data: [165000, 159000, 180000, 191000, 210000, 198000, 245680, 220000, 235000, 250000, 260000, 280000],
                    backgroundColor: gradient,
                    borderRadius: 10,
                    borderWidth: 0,
                    barPercentage: 0.7
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
                                return '₹' + (value / 1000) + 'k'; 
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
    });
</script>
@endpush
@endsection
