@extends('agency.layouts.app')

@section('title', 'Manage Bookings')
@section('page_title', 'Bookings Management')

@section('content')
<style>
    /* Exact CSS matching from Admin */
    .card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        padding: 1.5rem;
    }
    .form-input, .form-select {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        width: 100%;
        transition: border-color 0.2s;
    }
    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: #a8894d;
        box-shadow: 0 0 0 3px rgba(168, 137, 77, 0.1);
    }
    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-align: center;
    }
    .status-active { background-color: #10b981; color: white; }
    .status-inactive { background-color: #ef4444; color: white; }
    .status-pending { background-color: #f59e0b; color: white; }
    .bg-yellow-100 { background-color: #fef3c7; color: #92400e; }
    .bg-blue-100 { background-color: #dbeafe; color: #1e40af; }
    .bg-purple-100 { background-color: #f3e8ff; color: #6b21a8; }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.2s;
        cursor: pointer;
        border: 1px solid transparent;
    }
    .btn-primary { background-color: #a8894d; color: white; }
    .btn-primary:hover { background-color: #9d7c4f; }
    .btn-secondary { background-color: #17320b; color: white; }
    .btn-secondary:hover { background-color: #1a3a0f; }
    .btn-outline {
        background-color: transparent;
        color: #374151;
        border-color: #d1d5db;
    }
    .btn-outline:hover { background-color: #f9fafb; }
    .btn-danger { background-color: #ef4444; color: white; }
    .text-secondary { color: #a8894d; }
</style>

<div class="space-y-6">
    <!-- Header with Search and Filters -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#17320b]">Bookings</h1>
            <p class="text-gray-600">Manage all travel bookings for your packages</p>
        </div>
        <div class="flex items-center space-x-3">
            <form action="{{ route('agency.bookings') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search bookings..." class="form-input pl-10">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </form>
            <button onclick="exportBookings()" class="btn btn-outline">
                <i class="fas fa-download mr-2"></i> Export
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <p class="text-2xl font-bold text-[#17320b]">{{ $stats['total'] }}</p>
            <p class="text-gray-600">Total Clicks</p>
        </div>
        <!-- <div class="card">
            <p class="text-2xl font-bold text-[#17320b]">₹{{ number_format($stats['revenue']) }}</p>
            <p class="text-gray-600">Total Revenue</p>
        </div>
        <div class="card">
            <p class="text-2xl font-bold text-[#17320b]">{{ $stats['confirmed'] }}</p>
            <p class="text-gray-600">Confirmed</p>
        </div>
        <div class="card">
            <p class="text-2xl font-bold text-[#17320b]">{{ $stats['pending'] }}</p>
            <p class="text-gray-600">Pending</p>
        </div> -->
    </div>

    <!-- Filter Bar -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <form action="{{ route('agency.bookings') }}" method="GET" class="flex flex-wrap gap-3">
            <select name="status" class="form-select w-full md:w-auto" id="statusFilter">
                <option value="">All Status</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>

            <select name="payment_status" class="form-select w-full md:w-auto" id="paymentFilter">
                <option value="">Payment Status</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>

            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-input w-full md:w-auto" id="fromDate" placeholder="From Date">
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-input w-full md:w-auto" id="toDate" placeholder="To Date">

            <button type="submit" class="btn btn-outline">
                <i class="fas fa-filter mr-2"></i> Filter
            </button>
            <a href="{{ route('agency.bookings') }}" class="btn btn-outline">
                <i class="fas fa-redo mr-2"></i> Reset
            </a>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="bookingsTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Travel Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Travellers</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->booking_source }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->user->name ?? 'Guest' }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->user->phone ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500 truncate max-w-xs">{{ $booking->user->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $booking->package->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->package->duration ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $booking->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $booking->travel_date ? $booking->travel_date->format('d M Y') : 'TBD' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-secondary">₹{{ number_format($booking->total_amount) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $booking->travellers }} Person{{ $booking->travellers > 1 ? 's' : '' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'confirmed' => 'status-active',
                                        'pending' => 'bg-yellow-100',
                                        'cancelled' => 'status-inactive',
                                        'completed' => 'bg-blue-100'
                                    ];
                                    $sClass = $statusClasses[$booking->status] ?? 'bg-gray-100';
                                @endphp
                                <span class="badge {{ $sClass }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $paymentClasses = [
                                        'paid' => 'status-active',
                                        'pending' => 'bg-yellow-100',
                                        'refunded' => 'bg-purple-100'
                                    ];
                                    $pClass = $paymentClasses[$booking->payment_status] ?? 'bg-gray-100';
                                @endphp
                                <span class="badge {{ $pClass }}">
                                    {{ ucfirst($booking->payment_status) }}
                                </span>
                                <div class="text-xs text-gray-500 mt-1">{{ $booking->payment_method ?? 'TBD' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="viewBooking({{ $booking->id }})" class="text-purple-600 hover:text-purple-900" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="editBooking({{ $booking->id }})" class="text-blue-600 hover:text-blue-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="updateBookingStatus({{ $booking->id }}, '{{ $booking->status }}')" class="text-green-600 hover:text-green-900" title="Update Status">
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-10 text-center text-gray-500">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($bookings->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- View Booking Details Modal -->
<div id="viewBookingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-[#17320b]">Booking Details</h3>
                <button onclick="closeViewModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="viewBookingContent">
                <!-- Data injected via JS -->
            </div>
        </div>
    </div>
</div>

<!-- Edit Booking Modal -->
<div id="editBookingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-[#17320b]">Edit Booking</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="editBookingContent">
                <!-- Form injected via JS -->
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div id="updateStatusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-lg max-w-md w-full shadow-2xl">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-[#17320b]">Update Booking Status</h3>
                <button onclick="closeStatusModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="updateStatusContent">
                <!-- Content injected via JS -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const bookingsData = @json($bookings->items());

    function showLoading(show) {
        if (show) {
            const loading = document.createElement('div');
            loading.id = 'loadingOverlay';
            loading.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100]';
            loading.innerHTML = '<div class="bg-white p-6 rounded-lg shadow-lg flex items-center"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#a8894d]"></div><span class="ml-3 font-bold text-[#17320b]">Processing...</span></div>';
            document.body.appendChild(loading);
        } else {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) overlay.remove();
        }
    }

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-[100] p-4 rounded-lg shadow-lg flex items-center ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
        toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i><span>${message}</span>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    function viewBooking(id) {
        const booking = bookingsData.find(b => b.id == id);
        if (!booking) return;

        const content = `
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Booking ID</p>
                        <p class="font-bold text-lg text-[#17320b]">#BK-${String(booking.id).padStart(3, '0')}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Booking Source</p>
                        <p class="font-medium">${booking.booking_source || 'Website'}</p>
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <h4 class="font-bold text-lg mb-3 text-[#17320b]">Customer Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Name</p>
                            <p class="font-medium">${booking.user?.name || 'Guest'}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium">${booking.user?.email || 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Phone</p>
                            <p class="font-medium">${booking.user?.phone || 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Travellers</p>
                            <p class="font-medium">${booking.travellers || booking.number_of_travelers} Person${(booking.travellers || booking.number_of_travelers) > 1 ? 's' : ''}</p>
                        </div>
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <h4 class="font-bold text-lg mb-3 text-[#17320b]">Package Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Package Name</p>
                            <p class="font-medium">${booking.package?.name || 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Travel Date</p>
                            <p class="font-medium">${booking.travel_date ? new Date(booking.travel_date).toLocaleDateString() : 'TBD'}</p>
                        </div>
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <h4 class="font-bold text-lg mb-3 text-[#17320b]">Booking & Payment Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Amount</p>
                            <p class="font-bold text-[#a8894d] text-lg">₹${Number(booking.total_amount).toLocaleString()}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Payment Method</p>
                            <p class="font-medium">${booking.payment_method || 'N/A'}</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div>
                                <p class="text-sm text-gray-600">Booking Status</p>
                                <span class="badge ${getStatusClass(booking.status)}">${booking.status}</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Payment Status</p>
                                <span class="badge ${getPaymentStatusClass(booking.payment_status)}">${booking.payment_status}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="border-t pt-4 flex justify-end space-x-3">
                    <button onclick="editBooking(${booking.id})" class="btn btn-primary bg-[#a8894d] text-white">
                        <i class="fas fa-edit mr-2"></i> Edit Booking
                    </button>
                    <button onclick="window.print()" class="btn btn-secondary">
                        <i class="fas fa-file-invoice mr-2"></i> Generate Invoice
                    </button>
                </div>
            </div>
        `;
        document.getElementById('viewBookingContent').innerHTML = content;
        document.getElementById('viewBookingModal').classList.remove('hidden');
    }

    function editBooking(id) {
        const booking = bookingsData.find(b => b.id == id);
        if (!booking) return;

        const content = `
            <form action="/agency-panel/bookings/${id}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                
                <div>
                    <h4 class="font-bold text-lg mb-3 text-[#17320b]">Edit Booking Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Travel Date</label>
                            <input type="date" name="travel_date" class="form-input" value="${booking.travel_date ? booking.travel_date.split('T')[0] : ''}">
                        </div>
                        <div>
                            <label class="form-label">Number of Travellers</label>
                            <input type="number" name="travellers" class="form-input" min="1" value="${booking.travellers || booking.number_of_travelers}">
                        </div>
                        <div>
                            <label class="form-label">Amount (₹)</label>
                            <input type="number" name="total_amount" class="form-input" value="${booking.total_amount}">
                        </div>
                        <div>
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-input">
                                <option value="Credit Card" ${booking.payment_method === 'Credit Card' ? 'selected' : ''}>Credit Card</option>
                                <option value="Debit Card" ${booking.payment_method === 'Debit Card' ? 'selected' : ''}>Debit Card</option>
                                <option value="Net Banking" ${booking.payment_method === 'Net Banking' ? 'selected' : ''}>Net Banking</option>
                                <option value="UPI" ${booking.payment_method === 'UPI' ? 'selected' : ''}>UPI</option>
                                <option value="Cash" ${booking.payment_method === 'Cash' ? 'selected' : ''}>Cash</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-bold text-lg mb-3 text-[#17320b]">Status Management</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Booking Status</label>
                            <select name="status" class="form-input">
                                <option value="pending" ${booking.status === 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="confirmed" ${booking.status === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                                <option value="completed" ${booking.status === 'completed' ? 'selected' : ''}>Completed</option>
                                <option value="cancelled" ${booking.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-input">
                                <option value="pending" ${booking.payment_status === 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="paid" ${booking.payment_status === 'paid' ? 'selected' : ''}>Paid</option>
                                <option value="refunded" ${booking.payment_status === 'refunded' ? 'selected' : ''}>Refunded</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" onclick="closeEditModal()" class="btn btn-outline">Cancel</button>
                    <button type="submit" class="btn btn-primary bg-[#a8894d] text-white">Save Changes</button>
                </div>
            </form>
        `;
        document.getElementById('editBookingContent').innerHTML = content;
        document.getElementById('viewBookingModal').classList.add('hidden');
        document.getElementById('editBookingModal').classList.remove('hidden');
    }

    function updateBookingStatus(id, currentStatus) {
        const booking = bookingsData.find(b => b.id == id);
        const content = `
            <form action="/agency-panel/bookings/${id}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div>
                    <p class="text-gray-600 mb-2">Update status for booking <span class="font-bold">#BK-${String(id).padStart(3, '0')}</span></p>
                    <label class="form-label">New Booking Status</label>
                    <select name="status" class="form-select">
                        <option value="pending" ${currentStatus === 'pending' ? 'selected' : ''}>Pending</option>
                        <option value="confirmed" ${currentStatus === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                        <option value="completed" ${currentStatus === 'completed' ? 'selected' : ''}>Completed</option>
                        <option value="cancelled" ${currentStatus === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">New Payment Status</label>
                    <select name="payment_status" class="form-select">
                        <option value="pending" ${booking.payment_status === 'pending' ? 'selected' : ''}>Pending</option>
                        <option value="paid" ${booking.payment_status === 'paid' ? 'selected' : ''}>Paid</option>
                        <option value="refunded" ${booking.payment_status === 'refunded' ? 'selected' : ''}>Refunded</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeStatusModal()" class="btn btn-outline">Cancel</button>
                    <button type="submit" class="btn btn-primary bg-[#a8894d] text-white">Update Status</button>
                </div>
            </form>
        `;
        document.getElementById('updateStatusContent').innerHTML = content;
        document.getElementById('updateStatusModal').classList.remove('hidden');
    }

    function closeViewModal() { document.getElementById('viewBookingModal').classList.add('hidden'); }
    function closeEditModal() { document.getElementById('editBookingModal').classList.add('hidden'); }
    function closeStatusModal() { document.getElementById('updateStatusModal').classList.add('hidden'); }

    function getStatusClass(status) {
        if (status === 'confirmed') return 'status-active';
        if (status === 'pending') return 'bg-yellow-100';
        if (status === 'cancelled') return 'status-inactive';
        if (status === 'completed') return 'bg-blue-100';
        return 'bg-gray-100';
    }

    function getPaymentStatusClass(status) {
        if (status === 'paid') return 'status-active';
        if (status === 'pending') return 'bg-yellow-100';
        if (status === 'refunded') return 'bg-purple-100';
        return 'bg-gray-100';
    }

    function exportBookings() {
        showLoading(true);
        setTimeout(() => {
            showLoading(false);
            showToast('Bookings exported successfully');
        }, 1000);
    }
</script>
@endpush
