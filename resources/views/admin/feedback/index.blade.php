@extends('admin.layouts.app')

@section('title', 'User Feedback')

@section('content')
<!-- Page Title and Headers -->
<div class="mb-8">
    <div class="flex items-center gap-3 mb-3">
        <div class="w-1 h-8 bg-forest rounded-full"></div>
        <h1 class="text-3xl font-black text-forest font-poppins">User Feedback</h1>
    </div>
    <p class="text-slate text-sm font-medium ml-4">Manage and review feedback submitted by users.</p>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

<!-- Feedback Table Card -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Rating</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Message</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($feedbacks as $feedback)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-forest/10 flex items-center justify-center text-forest font-bold text-sm">
                                    {{ substr($feedback->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-admin-black text-sm">{{ $feedback->user->name ?? 'Guest User' }}</div>
                                    <div class="text-xs text-slate">{{ $feedback->user->email ?? 'No email' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge badge-info uppercase text-[10px]">
                                {{ ucfirst($feedback->category) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center text-gold text-xs gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $feedback->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star text-gray-300"></i>
                                    @endif
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($feedback->status == 'new')
                                <span class="bg-orange-100 text-orange-600 px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">New</span>
                            @else
                                <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Read</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate line-clamp-2 max-w-xs" title="{{ $feedback->message }}">
                                {{ $feedback->message }}
                            </p>
                            @if($feedback->admin_note)
                                <div class="mt-1 text-[10px] text-forest font-medium bg-forest/5 p-1 rounded border border-forest/10">
                                    <i class="fas fa-sticky-note mr-1"></i> {{ $feedback->admin_note }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-medium text-slate">
                                {{ $feedback->created_at->format('M d, Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditModal({{ $feedback->id }}, '{{ addslashes($feedback->admin_note) }}', '{{ $feedback->status }}')" 
                                        class="w-8 h-8 rounded-lg bg-forest/10 text-forest hover:bg-forest hover:text-white flex items-center justify-center transition-all duration-200" title="Management">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <form action="{{ route('admin.feedback.destroy', $feedback) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this feedback?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all duration-200" title="Delete Feedback">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-comment-slash text-2xl text-gray-300"></i>
                                </div>
                                <p class="text-base font-semibold">No feedback found</p>
                                <p class="text-xs text-gray-400 mt-1">Users haven't submitted any feedback yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($feedbacks->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $feedbacks->links() }}
        </div>
    @endif
</div>

<!-- Edit Feedback Modal -->
<div id="editFeedbackModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-forest/30 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeEditModal()"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <div class="bg-white px-8 pt-6 pb-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-black text-forest font-poppins" id="modal-title">Manage Feedback</h3>
                    <button onclick="closeEditModal()" class="text-slate hover:text-forest transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="editFeedbackForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate uppercase tracking-wider mb-2">Status</label>
                            <select name="status" id="feedback_status" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-forest/20 focus:border-forest transition-all">
                                <option value="new">New</option>
                                <option value="read">Read</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate uppercase tracking-wider mb-2">Admin Note</label>
                            <textarea name="admin_note" id="feedback_note" rows="4" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-forest/20 focus:border-forest transition-all" placeholder="Enter internal notes here..."></textarea>
                        </div>
                    </div>
                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="closeEditModal()" class="flex-1 px-6 py-3 rounded-xl border border-gray-100 text-slate font-bold text-sm hover:bg-gray-50 transition-all">Cancel</button>
                        <button type="submit" class="flex-1 px-6 py-3 rounded-xl bg-forest text-white font-bold text-sm hover:bg-forest-dark shadow-lg shadow-forest/20 transition-all">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openEditModal(id, note, status) {
        const modal = document.getElementById('editFeedbackModal');
        const form = document.getElementById('editFeedbackForm');
        const noteInput = document.getElementById('feedback_note');
        const statusInput = document.getElementById('feedback_status');
        
        form.action = `/admin/feedback/${id}`;
        noteInput.value = note;
        statusInput.value = status;
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        const modal = document.getElementById('editFeedbackModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endpush
@endsection
