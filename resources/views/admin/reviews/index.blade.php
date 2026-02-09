@extends('admin.layouts.app')

@section('title', 'User Reviews')

@section('content')
<!-- Success Messages -->
@if(session('success'))
<div id="toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('success') }}</span>
</div>
<script>
    setTimeout(() => {
        const toast = document.getElementById('toast');
        if (toast) toast.remove();
    }, 3000);
</script>
@endif

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-forest font-poppins">User Reviews</h1>
            <p class="text-slate font-medium">Manage user reviews and feedback</p>
        </div>
        <div class="flex space-x-2">
            <!-- Export button could go here if implemented -->
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="card p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-forest">{{ $totalReviews }}</p>
                    <p class="text-slate text-sm">Total Reviews</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-comments text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="card p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-forest">{{ $approvedReviews }}</p>
                    <p class="text-slate text-sm">Approved</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="card p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-forest">{{ $pendingReviews }}</p>
                    <p class="text-slate text-sm">Pending</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="card p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-forest">{{ number_format($averageRating, 1) }}/5</p>
                    <p class="text-slate text-sm">Avg Rating</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-star text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-3 sm:p-4 md:p-5 rounded-2xl shadow-soft border border-gray-100">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4">
            <select name="status" class="form-select w-full md:w-auto py-2" onchange="this.form.submit()">
                <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            </select>

            <select name="rating" class="form-select w-full md:w-auto py-2" onchange="this.form.submit()">
                <option value="all" {{ request('rating') === 'all' ? 'selected' : '' }}>All Ratings</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                        {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                    </option>
                @endfor
            </select>

            <select name="featured" class="form-select w-full md:w-auto py-2" onchange="this.form.submit()">
                <option value="all" {{ request('featured') === 'all' ? 'selected' : '' }}>All Reviews</option>
                <option value="yes" {{ request('featured') === 'yes' ? 'selected' : '' }}>Featured Only</option>
                <option value="no" {{ request('featured') === 'no' ? 'selected' : '' }}>Not Featured</option>
            </select>

            <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                <i class="fas fa-redo mr-2"></i> Reset
            </a>
        </form>
    </div>

    <!-- Reviews Table -->
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black text-forest uppercase tracking-wider font-poppins">User</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-forest uppercase tracking-wider font-poppins">Feedback</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-forest uppercase tracking-wider font-poppins">Category</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-forest uppercase tracking-wider font-poppins">Rating</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-forest uppercase tracking-wider font-poppins">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-forest uppercase tracking-wider font-poppins">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-forest uppercase tracking-wider font-poppins">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <!-- User Column -->
                        <td class="px-6 py-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 bg-forest/5 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-black text-forest">
                                        {{ strtoupper(substr($review->user->name ?? 'Guest', 0, 1)) }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-forest font-poppins">
                                        {{ $review->user->name ?? 'Guest' }}
                                        @if($review->featured)
                                            <span class="ml-2 text-[10px] bg-gold text-white px-2 py-0.5 rounded uppercase tracking-wider">Featured</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate mt-1">
                                        {{ $review->user->email ?? '' }}
                                    </div>
                                    <div class="text-[10px] text-slate/70 mt-1">
                                        ID: {{ $review->user_id }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Feedback Column -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-forest font-bold mb-1">
                                {{ $review->title ?? 'No Title' }}
                            </div>
                            <div class="text-sm text-slate">
                                {{ Str::limit($review->comment, 50) }}
                            </div>
                            @if($review->helpful_count > 0)
                                <div class="text-xs text-slate/70 mt-1">
                                    <i class="fas fa-thumbs-up text-slate/50 mr-1"></i>
                                    {{ $review->helpful_count }} found helpful
                                </div>
                            @endif
                        </td>
                        
                        <!-- Category Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate font-medium">
                                {{ $review->category ?? 'Not specified' }}
                            </div>
                            <div class="text-xs text-slate/70 mt-1">
                                {{ $review->package->name ?? 'Package Removed' }}
                            </div>
                        </td>
                        
                        <!-- Rating Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                                <span class="ml-2 text-sm font-bold text-forest">{{ $review->rating }}/5</span>
                            </div>
                        </td>
                        
                        <!-- Date Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-forest font-medium">
                                {{ $review->created_at->format('d M Y') }}
                            </div>
                            <div class="text-xs text-slate">
                                {{ $review->created_at->format('h:i A') }}
                            </div>
                        </td>
                        
                        <!-- Status Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($review->status === 'approved')
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-50 border border-green-200">
                                    <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-green-600">Approved</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-yellow-50 border border-yellow-200">
                                    <div class="w-1.5 h-1.5 rounded-full bg-yellow-500"></div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-yellow-600">Pending</span>
                                </span>
                            @endif
                        </td>
                        
                        <!-- Actions Column -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="viewReview({{ $review->toJson() }}, {{ $review->user ? $review->user->toJson() : '{}' }}, {{ $review->package ? $review->package->toJson() : '{}' }})" 
                                        class="text-purple-600 hover:text-purple-900" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="replyToReview({{ $review->id }}, '{{ addslashes($review->title ?? '') }}', '{{ addslashes($review->user->name ?? 'Guest') }}', '{{ addslashes(Str::limit($review->comment, 100)) }}', {{ $review->rating }})" 
                                        class="text-blue-600 hover:text-blue-900" title="Reply">
                                    <i class="fas fa-reply"></i>
                                </button>
                                <button onclick="updateReviewStatus({{ $review->id }}, '{{ $review->status }}', '{{ addslashes($review->user->name ?? 'Guest') }}', '{{ $review->category ?? 'General' }}', '{{ addslashes($review->package->name ?? '') }}')" 
                                        class="text-green-600 hover:text-green-900" title="Update Status">
                                    <i class="fas fa-sync"></i>
                                </button>
                                <form action="{{ route('admin.reviews.toggleFeatured') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="review_id" value="{{ $review->id }}">
                                    <button type="submit" 
                                            class="{{ $review->featured ? 'text-yellow-600 hover:text-yellow-900' : 'text-gray-400 hover:text-gray-600' }}" 
                                            title="Toggle Featured">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.reviews.destroy') }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                    @csrf
                                    <input type="hidden" name="review_id" value="{{ $review->id }}">
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-comment-alt text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate mb-2 font-poppins">No reviews found</h3>
                                <p class="text-sm text-gray-500">Try adjusting your filters</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reviews->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $reviews->links() }}
        </div>
        @endif
    </div>
</div>

<!-- View Review Modal -->
<div id="viewReviewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-luxury">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold font-poppins text-forest">Review Details</h3>
                <button onclick="closeViewModal()" class="text-slate hover:text-forest">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="reviewDetailsContent">
                <!-- Content loaded via JS -->
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div id="replyModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-luxury">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold font-poppins text-forest">Reply to Review</h3>
                <button onclick="closeReplyModal()" class="text-slate hover:text-forest">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.reviews.reply') }}" method="POST">
                @csrf
                <input type="hidden" name="review_id" id="replyReviewId">

                <div class="space-y-4">
                    <div id="replyReviewPreview">
                        <!-- Preview loaded via JS -->
                    </div>

                    <div>
                        <label class="form-label">Reply From</label>
                        <input type="text" name="reply_by" value="ZUBEEE Admin" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Your Reply *</label>
                        <textarea name="reply_text" rows="4" required class="form-textarea" placeholder="Type your reply here..."></textarea>
                    </div>

                    <div class="pt-2 flex justify-end space-x-3">
                        <button type="button" onclick="closeReplyModal()" class="btn btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane mr-2"></i> Send Reply
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div id="updateStatusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-luxury">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold font-poppins text-forest">Update Review Status</h3>
                <button onclick="closeStatusModal()" class="text-slate hover:text-forest">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.reviews.status') }}" method="POST" id="statusForm">
                @csrf
                <input type="hidden" name="review_id" id="statusReviewId">

                <div class="space-y-4">
                    <div id="statusReviewPreview">
                         <!-- Preview loaded via JS -->
                    </div>

                    <div>
                        <label class="form-label">New Status</label>
                        <select name="status" class="form-select">
                            <option value="approved">Approved</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>

                    <div class="pt-2 flex justify-end space-x-3">
                        <button type="button" onclick="closeStatusModal()" class="btn btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i> Update Status
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function viewReview(review, user, package) {
        const modalContent = document.getElementById('reviewDetailsContent');
        const userName = user.name || 'Guest';
        const userEmail = user.email || '';
        const packageName = package.name || '—';
        const packageId = package.id || '—';
        const ratingStars = getStarsHTML(review.rating);
        
        // Replies HTML
        let repliesHtml = '';
        if(review.replies && review.replies.length > 0) {
            repliesHtml += '<div class="border-t pt-4 mt-6"><h5 class="font-bold text-forest mb-3">Replies</h5><div class="space-y-3">';
            review.replies.forEach(reply => {
                repliesHtml += `
                    <div class="bg-gray-50 p-4 rounded-lg relative group">
                        <div class="flex justify-between mb-1">
                            <span class="font-bold text-sm text-forest">${reply.reply_by || 'Admin'}</span>
                             <span class="text-xs text-slate">${new Date(reply.created_at).toLocaleDateString()}</span>
                        </div>
                        <p class="text-slate text-sm">${reply.reply_text}</p>
                         <form action="{{ route('admin.reviews.reply.delete') }}" method="POST" class="absolute top-2 right-2 hidden group-hover:block" onsubmit="return confirm('Delete this reply?');">
                            @csrf
                            <input type="hidden" name="reply_id" value="${reply.id}">
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs"><i class="fas fa-trash"></i></button>
                         </form>
                    </div>
                `;
            });
            repliesHtml += '</div></div>';
        }

        modalContent.innerHTML = `
            <div class="space-y-6">
                <!-- Review Header -->
                <div class="flex items-start justify-between">
                    <div class="flex items-center">
                        <div class="h-12 w-12 bg-forest/5 rounded-full flex items-center justify-center">
                            <span class="text-lg font-bold text-forest">
                                ${userName.charAt(0).toUpperCase()}
                            </span>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-bold text-forest font-poppins">${userName}</h4>
                            <p class="text-slate text-sm">${userEmail}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-forest">${review.rating}/5</div>
                        <div class="flex items-center justify-end mt-1 text-yellow-400">
                            ${ratingStars}
                        </div>
                    </div>
                </div>
                
                <!-- Package Info -->
                <div class="bg-champagne/30 p-4 rounded-xl border border-gold/10 flex justify-between items-center">
                    <div>
                        <div class="text-xs text-slate uppercase tracking-wider font-bold">Package</div>
                        <div class="text-forest font-bold">${packageName}</div>
                    </div>
                     <div>
                        <div class="text-xs text-slate uppercase tracking-wider font-bold">Category</div>
                        <div class="text-forest font-medium">${review.category || '—'}</div>
                    </div>
                </div>
                
                <!-- Feedback Content -->
                <div>
                    <h5 class="font-bold text-forest mb-2">Feedback</h5>
                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                        <h6 class="font-bold text-forest mb-2">${review.title || ''}</h6>
                        <div class="text-slate italic leading-relaxed">"${review.comment}"</div>
                    </div>
                </div>

                ${repliesHtml}
                
                <!-- Actions -->
                <div class="border-t pt-6 flex justify-end space-x-3">
                    <button onclick="document.getElementById('viewReviewModal').click(); replyToReview(${review.id}, '${(review.title || '').replace(/'/g, "\\'")}', '${userName.replace(/'/g, "\\'")}', '${review.comment.substring(0, 100).replace(/'/g, "\\'")}', ${review.rating})" class="btn btn-primary">
                        <i class="fas fa-reply mr-2"></i> Reply
                    </button>
                </div>
            </div>
        `;

        document.getElementById('viewReviewModal').classList.remove('hidden');
    }

    function replyToReview(id, title, userName, feedback, rating) {
        document.getElementById('replyReviewId').value = id;
        
        const previewContent = document.getElementById('replyReviewPreview');
        const stars = getStarsHTML(rating);
        
        previewContent.innerHTML = `
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <div class="flex items-start">
                    <div class="flex-1">
                        <p class="font-bold text-forest text-sm">${userName}</p>
                        <p class="text-xs text-slate font-medium mt-0.5">${title}</p>
                        <p class="text-xs text-slate/70 mt-1 italic">"${feedback}..."</p>
                    </div>
                    <div class="text-right">
                         <div class="flex text-yellow-400 text-xs">
                            ${stars}
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('replyModal').classList.remove('hidden');
        // Close view modal if open
        closeViewModal();
    }

    function updateReviewStatus(id, currentStatus, userName, category, packageName) {
        document.getElementById('statusReviewId').value = id;
        const form = document.getElementById('statusForm');
        form.querySelector('select[name="status"]').value = currentStatus;

        const previewContent = document.getElementById('statusReviewPreview');
        previewContent.innerHTML = `
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-bold text-forest text-sm">${userName}</p>
                        <p class="text-xs text-slate mt-0.5 text-medium">${category}</p>
                        <p class="text-xs text-slate/70">${packageName}</p>
                    </div>
                    <div>
                        <span class="badge ${currentStatus === 'approved' ? 'badge-success' : 'badge-warning'}">
                            ${currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1)}
                        </span>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('updateStatusModal').classList.remove('hidden');
    }

    function closeViewModal() {
        document.getElementById('viewReviewModal').classList.add('hidden');
    }

    function closeReplyModal() {
        document.getElementById('replyModal').classList.add('hidden');
    }

    function closeStatusModal() {
        document.getElementById('updateStatusModal').classList.add('hidden');
    }

    function getStarsHTML(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += `<i class="${i <= rating ? 'fas' : 'far'} fa-star"></i>`;
        }
        return stars;
    }
</script>
@endpush

@endsection
