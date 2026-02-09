@extends('admin.layouts.app')
@section('title', 'Manage Users')
@section('page_title', 'Users Management')
@section('content')
<div class="space-y-6">
    <!-- Header with Search and Add Button -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Users</h1>
            <p class="text-gray-600">Manage registered users and their accounts</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <input type="text" placeholder="Search users..." class="form-input pl-10" id="searchInput">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Add User
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-blue-100 mr-4">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                    <p class="text-gray-600">Total Users</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-green-100 mr-4">
                    <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeUsers }}</p>
                    <p class="text-gray-600">Active Users</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-purple-100 mr-4">
                    <i class="fas fa-suitcase text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalBookings }}</p>
                    <p class="text-gray-600">Total Bookings</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-orange-100 mr-4">
                    <i class="fas fa-rupee-sign text-orange-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($totalRevenue) }}</p>
                    <p class="text-gray-600">Total Revenue</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <div class="flex flex-wrap gap-3">
            <select class="form-select w-full md:w-auto" id="statusFilter">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="pending">Pending</option>
            </select>
            <select class="form-select w-full md:w-auto" id="roleFilter">
                <option value="">All Roles</option>
                <option value="user">Regular User</option>
                <option value="agent">Travel Agent</option>
                <option value="admin">Administrator</option>
            </select>
            <select class="form-select w-full md:w-auto" id="stateFilter">
                <option value="">All States</option>
                @php
                    $states = $users->pluck('state')->unique()->filter()->sort();
                @endphp
                @foreach($states as $state)
                    <option value="{{ $state }}">{{ $state }}</option>
                @endforeach
            </select>
            <button class="btn btn-outline" onclick="resetFilters()">
                <i class="fas fa-redo mr-2"></i> Reset Filters
            </button>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="overflow-x-auto">
            <table id="usersTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-[#2d5f3f] rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->phone ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">Since: {{ $user->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->city ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $user->state ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $roleClasses = [
                                        'admin' => 'bg-red-100 text-red-800',
                                        'agent' => 'bg-green-100 text-green-800',
                                        'user' => 'bg-blue-100 text-blue-800',
                                    ];
                                    $roleIcons = [
                                        'admin' => 'fa-user-shield',
                                        'agent' => 'fa-user-tie',
                                        'user' => 'fa-user',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $roleClasses[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                    <i class="fas {{ $roleIcons[$user->role] ?? 'fa-user' }} mr-1"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'active' => 'bg-green-100 text-green-800',
                                        'inactive' => 'bg-red-100 text-red-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                    ];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$user->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="viewUser({{ $user->id }})" class="text-purple-600 hover:text-purple-900" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <button onclick="previousPage()" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </button>
                <button onclick="nextPage()" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span id="startIndex" class="font-medium">1</span> to
                        <span id="endIndex" class="font-medium">10</span> of
                        <span id="totalItems" class="font-medium">{{ $users->count() }}</span> results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <button onclick="previousPage()" id="prevBtn" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Previous</span>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div id="paginationNumbers" class="flex"></div>
                        <button onclick="nextPage()" id="nextBtn" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Next</span>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View User Details Modal -->
<div id="viewUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">User Details</h3>
                <button onclick="closeViewModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="userDetailsContent"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
const usersData = @json($users);
let currentPage = 1;
const rowsPerPage = 10;

document.addEventListener('DOMContentLoaded', function() {
    setupPagination();
    setupFilters();
    setupSearch();
});

function setupPagination() {
    updatePagination();
    renderTable();
}

function setupFilters() {
    document.getElementById('statusFilter').addEventListener('change', filterTable);
    document.getElementById('roleFilter').addEventListener('change', filterTable);
    document.getElementById('stateFilter').addEventListener('change', filterTable);
}

function setupSearch() {
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#usersTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
        
        updatePaginationDisplay();
    });
}

function filterTable() {
    const status = document.getElementById('statusFilter').value.toLowerCase();
    const role = document.getElementById('roleFilter').value.toLowerCase();
    const state = document.getElementById('stateFilter').value.toLowerCase();
    
    const rows = document.querySelectorAll('#usersTable tbody tr');
    
    rows.forEach(row => {
        const cells = row.cells;
        const rowRole = cells[4].textContent.toLowerCase();
        const rowStatus = cells[5].textContent.toLowerCase();
        const rowLocation = cells[3].textContent.toLowerCase();
        
        let showRow = true;
        
        if (status && !rowStatus.includes(status)) showRow = false;
        if (role && !rowRole.includes(role)) showRow = false;
        if (state && !rowLocation.includes(state)) showRow = false;
        
        row.style.display = showRow ? '' : 'none';
    });
    
    updatePaginationDisplay();
}

function resetFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('roleFilter').value = '';
    document.getElementById('stateFilter').value = '';
    document.getElementById('searchInput').value = '';
    
    const rows = document.querySelectorAll('#usersTable tbody tr');
    rows.forEach(row => row.style.display = '');
    
    updatePaginationDisplay();
}

function updatePaginationDisplay() {
    const visibleRows = Array.from(document.querySelectorAll('#usersTable tbody tr'))
        .filter(row => row.style.display !== 'none');
    
    const totalVisible = visibleRows.length;
    const totalPages = Math.ceil(totalVisible / rowsPerPage);
    
    const startIndex = Math.min((currentPage - 1) * rowsPerPage + 1, totalVisible);
    const endIndex = Math.min(currentPage * rowsPerPage, totalVisible);
    
    document.getElementById('startIndex').textContent = totalVisible > 0 ? startIndex : 0;
    document.getElementById('endIndex').textContent = endIndex;
    document.getElementById('totalItems').textContent = totalVisible;
    
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages || totalPages === 0;
    
    if (prevBtn.disabled) {
        prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
    
    if (nextBtn.disabled) {
        nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

function renderTable() {
    const rows = document.querySelectorAll('#usersTable tbody tr');
    const startIndex = (currentPage - 1) * rowsPerPage;
    const endIndex = startIndex + rowsPerPage;
    
    let visibleRowIndex = 0;
    rows.forEach((row, index) => {
        if (row.style.display !== 'none') {
            if (visibleRowIndex >= startIndex && visibleRowIndex < endIndex) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
            visibleRowIndex++;
        }
    });
    
    updatePaginationDisplay();
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        renderTable();
    }
}

function nextPage() {
    const totalVisible = Array.from(document.querySelectorAll('#usersTable tbody tr'))
        .filter(row => row.style.display !== 'none').length;
    const totalPages = Math.ceil(totalVisible / rowsPerPage);
    
    if (currentPage < totalPages) {
        currentPage++;
        renderTable();
    }
}

function updatePagination() {
    currentPage = 1;
    renderTable();
}

function viewUser(id) {
    const user = usersData.find(u => u.id == id);
    
    if (user) {
        const modalContent = document.getElementById('userDetailsContent');
        modalContent.innerHTML = `
            <div class="space-y-6">
                <div class="flex items-center space-x-6">
                    <div class="flex-shrink-0 h-24 w-24 bg-[#2d5f3f] rounded-full flex items-center justify-center">
                        <span class="text-white text-3xl font-bold">${user.name.charAt(0).toUpperCase()}</span>
                    </div>
                    <div>
                        <h4 class="text-2xl font-bold">${user.name}</h4>
                        <p class="text-gray-600">${user.email}</p>
                        <div class="flex items-center space-x-4 mt-3">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${user.status === 'active' ? 'bg-green-100 text-green-800' : user.status === 'inactive' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'}">
                                ${user.status.charAt(0).toUpperCase() + user.status.slice(1)}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${user.role === 'admin' ? 'bg-red-100 text-red-800' : user.role === 'agent' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}">
                                ${user.role.charAt(0).toUpperCase() + user.role.slice(1)}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <h5 class="font-bold text-lg mb-2">Contact Information</h5>
                            <div class="space-y-2">
                                <p><span class="font-medium">Phone:</span> ${user.phone || 'N/A'}</p>
                                <p><span class="font-medium">Location:</span> ${user.city || 'N/A'}, ${user.state || ''}, ${user.country || ''}</p>
                                <p><span class="font-medium">Member Since:</span> ${new Date(user.created_at).toLocaleDateString()}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="font-bold text-lg mb-3">Quick Actions</h5>
                        <div class="space-y-3">
                            <a href="/admin/users/${user.id}/edit" class="w-full btn btn-primary block text-center">
                                <i class="fas fa-edit mr-2"></i> Edit User Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('viewUserModal').classList.remove('hidden');
    }
}

function closeViewModal() {
    document.getElementById('viewUserModal').classList.add('hidden');
}
</script>
@endpush
