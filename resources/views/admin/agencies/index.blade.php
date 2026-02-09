@extends('admin.layouts.app')

@section('title', 'Manage Agencies')
@section('page_title', 'Agencies Management')

@section('content')
<div class="space-y-6">
    <!-- Header with Search and Add Button -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-forest font-poppins">Travel Agencies</h1>
            <p class="text-gray-600">Manage partner travel agencies</p>
        </div>
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <form action="{{ route('admin.agencies.index') }}" method="GET" class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search agencies..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-forest focus:border-forest">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </form>
            <a href="{{ route('admin.agencies.create') }}" class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-2 bg-forest text-white font-bold rounded-xl hover:bg-forest-dark transition-all duration-300">
                <i class="fas fa-plus"></i> Add Agency
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
        <form action="{{ route('admin.agencies.index') }}" method="GET" class="flex flex-wrap gap-3" id="filterForm">
            <select name="status" class="px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-forest focus:border-forest text-sm bg-white" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
            
            <select name="state" class="px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-forest focus:border-forest text-sm bg-white" onchange="this.form.submit()">
                <option value="">All States</option>
                @foreach($states as $state)
                    <option value="{{ $state }}" {{ request('state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                @endforeach
            </select>

            <select name="experience" class="px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-forest focus:border-forest text-sm bg-white" onchange="this.form.submit()">
                <option value="">All Experience</option>
                <option value="1-5" {{ request('experience') == '1-5' ? 'selected' : '' }}>1-5 Years</option>
                <option value="6-10" {{ request('experience') == '6-10' ? 'selected' : '' }}>6-10 Years</option>
                <option value="11-15" {{ request('experience') == '11-15' ? 'selected' : '' }}>11-15 Years</option>
                <option value="16+" {{ request('experience') == '16+' ? 'selected' : '' }}>16+ Years</option>
            </select>

            <a href="{{ route('admin.agencies.index') }}" class="px-4 py-2 border border-gray-200 rounded-xl hover:bg-gray-100 text-sm font-medium flex items-center gap-2 bg-white">
                <i class="fas fa-redo"></i> Reset
            </a>
        </form>
    </div>

    <!-- Agencies Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-wider">Agency Info</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-wider">Stats</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-wider">Commission</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($agencies as $agency)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    @if($agency->logo)
                                        <img src="{{ asset('storage/' . $agency->logo) }}" alt="{{ $agency->name }}" class="w-12 h-12 object-cover rounded-xl border border-gray-100 shadow-sm">
                                    @else
                                        <div class="w-12 h-12 bg-forest/5 text-forest font-bold rounded-xl flex items-center justify-center border border-forest/10 uppercase">
                                            {{ substr($agency->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $agency->name }}</div>
                                        <div class="flex items-center gap-1 mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star text-[10px] {{ $i <= $agency->rating ? 'text-gold' : 'text-gray-200' }}"></i>
                                            @endfor
                                            <span class="text-[10px] font-bold text-gray-500 ml-1">{{ number_format($agency->rating, 1) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $agency->contact_person ?? 'N/A' }}</div>
                                <div class="text-[11px] text-gray-500 font-medium">{{ $agency->phone }}</div>
                                <div class="text-[11px] text-gray-400">{{ $agency->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $agency->city ?? 'N/A' }}</div>
                                <div class="text-[11px] text-gray-500 uppercase font-black tracking-wider">{{ $agency->state ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-700">{{ $agency->experience_years }} Years Exp.</div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600 mt-1">
                                    {{ $agency->packages_count }} Packages
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-black text-forest">{{ $agency->commission_percentage }}%</div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ str_replace('_', ' ', $agency->payment_terms ?? 'Default') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'active' => 'bg-green-50 text-green-600 border-green-100',
                                        'inactive' => 'bg-red-50 text-red-600 border-red-100',
                                        'pending' => 'bg-yellow-50 text-yellow-600 border-yellow-100'
                                    ];
                                    $class = $statusClasses[$agency->status] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                                @endphp
                                <span class="px-2 py-1 rounded-lg text-[10px] font-black uppercase border {{ $class }}">
                                    {{ $agency->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.agencies.edit', $agency) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="viewAgency({{ $agency->id }})" class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.agencies.impersonate', $agency) }}" class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Login as Agent">
                                        <i class="fas fa-user-secret"></i>
                                    </a>
                                    <form action="{{ route('admin.agencies.destroy', $agency) }}" method="POST" class="inline-block" onsubmit="return confirm('Archive this agency?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 font-medium">No agencies found matching your criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($agencies->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $agencies->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Simple View Modal (handled partially by Alpine or similar if needed, or just dummy for now) -->
<div id="agencyModal" class="hidden fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
        <div id="modalContent" class="p-8"></div>
        <div class="px-8 py-4 bg-gray-50 border-t flex justify-end">
            <button onclick="closeModal()" class="px-6 py-2 bg-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-300 transition-colors">Close</button>
        </div>
    </div>
</div>

<script>
    function closeModal() {
        document.getElementById('agencyModal').classList.add('hidden');
    }

    function viewAgency(id) {
        // Simple mock for now - in production you'd fetch details or have them ready
        const modal = document.getElementById('agencyModal');
        const content = document.getElementById('modalContent');
        content.innerHTML = '<p class="text-center py-8">Loading details for agency ID: ' + id + '...</p>';
        modal.classList.remove('hidden');
        
        // You could fetch via AJAX here
        fetch(`{{ url('admin/agencies') }}/${id}`)
            .then(response => response.text())
            .then(html => {
                // Parse the response if it's a full page or just return partial
                // For simplicity, we'll just redirect to the show page or show a toast
                window.location.href = `{{ url('admin/agencies') }}/${id}`;
            });
    }
</script>
@endsection
