@extends('admin.layouts.app')

@section('title', 'Application Details')
@section('page_title', 'Partner Application Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.partner-applications.index') }}" class="text-[#a8894d] hover:text-[#17320b] font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to Applications
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Application Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-[#17320b]">Application Status</h2>
                    @if($application->status == 'pending')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Pending Review
                        </span>
                    @elseif($application->status == 'approved')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            Approved
                        </span>
                    @else
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            Rejected
                        </span>
                    @endif
                </div>
                <div class="text-sm text-gray-600">
                    <p>Submitted: {{ $application->created_at->format('F d, Y \a\t h:i A') }}</p>
                    @if($application->reviewed_at)
                        <p>Reviewed: {{ $application->reviewed_at->format('F d, Y \a\t h:i A') }}</p>
                        @if($application->reviewer)
                            <p>Reviewed by: {{ $application->reviewer->name }}</p>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Agency Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-[#17320b] mb-4">Agency Information</h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Agency Name</label>
                        <p class="text-gray-900">{{ $application->agency_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Business Email</label>
                        <p class="text-gray-900">{{ $application->business_email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Phone Number</label>
                        <p class="text-gray-900">{{ $application->phone }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Address</label>
                        <p class="text-gray-900">{{ $application->address }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Business Description</label>
                        <p class="text-gray-900">{{ $application->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Admin Notes -->
            @if($application->admin_notes)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-[#17320b] mb-4">Admin Notes</h2>
                <p class="text-gray-700">{{ $application->admin_notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Applicant Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-[#17320b] mb-4">Applicant</h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Name</label>
                        <p class="text-gray-900">{{ $application->user->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900">{{ $application->user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Phone</label>
                        <p class="text-gray-900">{{ $application->user->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Member Since</label>
                        <p class="text-gray-900">{{ $application->user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if($application->status == 'pending')
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-[#17320b] mb-4">Actions</h2>
                
                <!-- Approve Form -->
                <form action="{{ route('admin.partner-applications.approve', $application) }}" method="POST" class="mb-4">
                    @csrf
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to approve this application? This will create a new agency and link the user account.')"
                            class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition font-medium">
                        <i class="fas fa-check mr-2"></i>Approve Application
                    </button>
                </form>

                <!-- Reject Form -->
                <button onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                        class="w-full bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition font-medium">
                    <i class="fas fa-times mr-2"></i>Reject Application
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-bold text-[#17320b] mb-4">Reject Application</h3>
        <form action="{{ route('admin.partner-applications.reject', $application) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection</label>
                <textarea name="reason" rows="4" required
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#a8894d]"
                          placeholder="Please provide a reason for rejection..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" 
                        onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition">
                    Reject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
