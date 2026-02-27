@extends('admin.layouts.app')

@section('title', 'User Reviews')

@section('content')
{{-- 
<!-- Success Messages -->
... (rest of the content commented out)
--}}
<div class="flex items-center justify-center min-h-[400px]">
    <div class="text-center">
        <h2 class="text-2xl font-bold text-forest mb-2">Page Disabled</h2>
        <p class="text-slate">This page has been hidden as requested.</p>
    </div>
</div>
@endsectionh('scripts')
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
