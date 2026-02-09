@extends('admin.layouts.app')

@section('title', 'Package Agencies')

@section('content')
<div class="space-y-6">
     <!-- Header with Breadcrumb -->
     <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.packages.index') }}" class="text-secondary hover:underline">Packages</a>
                <i class="fas fa-chevron-right mx-2"></i>
                <a href="{{ route('admin.packages.edit', $package) }}" class="text-secondary hover:underline">Edit Package</a>
                <i class="fas fa-chevron-right mx-2"></i>
                <span>Package Agencies</span>
            </div>
            <div class="flex items-center space-x-4">
                <h1 class="text-2xl font-bold text-gray-800">Package Agencies</h1>
                <span class="badge bg-blue-100 text-blue-800">{{ $package->packageAgencies->count() }} Agencies</span>
            </div>
            <p class="text-gray-600">Manage agencies offering this package</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-outline">
                <i class="fas fa-arrow-left mr-2"></i> Back to Package
            </a>
            <button type="button" onclick="document.getElementById('addAgencyModal').classList.remove('hidden')" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Add Agency
            </button>
        </div>
    </div>

    <!-- Package Summary -->
    <div class="card flex items-center space-x-4">
        <img src="{{ $package->image }}" alt="{{ $package->name }}" class="w-24 h-24 object-cover rounded-lg">
        <div>
            <h2 class="text-xl font-bold text-gray-800">{{ $package->name }}</h2>
            <p class="text-gray-600">{{ $package->location }}</p>
            <div class="flex items-center space-x-4 mt-2">
                <div class="text-secondary font-bold">₹{{ number_format($package->price) }}</div>
                <div class="text-gray-600">{{ $package->duration }}</div>
            </div>
        </div>
    </div>

    <!-- Agencies Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agency</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration & Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($package->packageAgencies as $agency)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $agency->agency->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $agency->agency->email }}</div>
                                <div class="text-sm text-gray-500">{{ $agency->agency->phone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-secondary">₹{{ number_format($agency->price) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $agency->duration }}</div>
                                <div class="text-sm text-gray-500">{{ $agency->date }}</div>
                            </td>
                             <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $agency->commission }}%</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge {{ $agency->status == 'active' ? 'badge-success' : 'badge-danger' }}">
                                    {{ ucfirst($agency->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="editAgency({{ $agency->toJson() }})" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                     <form action="{{ route('admin.package-agencies.destroy', $agency) }}" method="POST" onsubmit="return confirm('Remove this agency from package?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-building text-gray-400 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Agencies Found</h3>
                                    <p class="text-gray-600 mb-4">No agencies are currently offering this package.</p>
                                    <button onclick="document.getElementById('addAgencyModal').classList.remove('hidden')" class="btn btn-primary">
                                        <i class="fas fa-plus mr-2"></i> Add First Agency
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Agency Modal -->
<div id="addAgencyModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Add Agency to Package</h3>
                <button onclick="document.getElementById('addAgencyModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('admin.packages.agencies.store', $package) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label">Select Agency *</label>
                    <select name="agency_id" class="form-select w-full" required>
                        <option value="">Choose an agency</option>
                        @foreach($allAgencies as $agency)
                            <option value="{{ $agency->id }}">{{ $agency->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Price (₹) *</label>
                        <input type="number" name="price" class="form-input" required min="0" step="1" placeholder="e.g., 16000">
                    </div>
                    <div>
                        <label class="form-label">Commission (%) *</label>
                        <input type="number" name="commission" class="form-input" required min="0" max="100" step="0.1" value="15">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Duration *</label>
                        <input type="text" name="duration" class="form-input" required placeholder="e.g., 10 days/9 Nights">
                    </div>
                    <div>
                        <label class="form-label">Available Date *</label>
                        <input type="text" name="date" class="form-input" required placeholder="e.g., 6 Dec">
                    </div>
                </div>

                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select w-full">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" onclick="document.getElementById('addAgencyModal').classList.add('hidden')" class="btn btn-outline">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Agency</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Agency Modal -->
<div id="editAgencyModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-lg max-w-md w-full">
        <div class="p-6">
            <h3 class="text-xl font-bold mb-4">Edit Agency Details</h3>
            <form id="editAgencyForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                 <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Price (₹) *</label>
                        <input type="number" name="price" id="edit_price" class="form-input" required min="0" step="1">
                    </div>
                    <div>
                        <label class="form-label">Commission (%) *</label>
                        <input type="number" name="commission" id="edit_commission" class="form-input" required min="0" max="100" step="0.1">
                    </div>
                </div>
                 <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Duration *</label>
                        <input type="text" name="duration" id="edit_duration" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Available Date *</label>
                        <input type="text" name="date" id="edit_date" class="form-input" required>
                    </div>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" id="edit_status" class="form-select w-full">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="document.getElementById('editAgencyModal').classList.add('hidden')" class="btn btn-outline">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function editAgency(agency) {
        document.getElementById('edit_price').value = agency.price;
        document.getElementById('edit_commission').value = agency.commission;
        document.getElementById('edit_duration').value = agency.duration;
        document.getElementById('edit_date').value = agency.date;
        document.getElementById('edit_status').value = agency.status;
        
        let form = document.getElementById('editAgencyForm');
        form.action = `/admin/package-agencies/${agency.id}`;
        
        document.getElementById('editAgencyModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection
