<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'package'])->paginate(10);
        
        // Calculate stats
        $totalReviews = Review::count();
        $approvedReviews = Review::where('status', 'approved')->count();
        $pendingReviews = Review::where('status', 'pending')->count();
        $averageRating = Review::avg('rating') ?? 0;
        
        return view('admin.reviews.index', compact(
            'reviews',
            'totalReviews',
            'approvedReviews',
            'pendingReviews',
            'averageRating'
        ));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'review_id' => 'required|exists:reviews,id',
            'status' => 'required|in:approved,pending',
        ]);

        $review = Review::findOrFail($request->review_id);
        $review->status = $request->status;
        $review->save();

        return back()->with('success', 'Review status updated successfully.');
    }

    public function reply(Request $request)
    {
        $request->validate([
            'review_id' => 'required|exists:reviews,id',
            'reply_text' => 'required|string',
        ]);

        $review = Review::findOrFail($request->review_id);
        
        $replies = $review->replies ?? [];
        $replies[] = [
            'id' => uniqid(),
            'reply_by' => $request->reply_by ?? 'Admin',
            'reply_text' => $request->reply_text,
            'created_at' => now()->toDateTimeString(),
        ];
        
        $review->replies = $replies;
        $review->save();

        return back()->with('success', 'Reply added successfully.');
    }

    public function deleteReply(Request $request)
    {
        $request->validate([
            'reply_id' => 'required',
        ]);

        $review = Review::where('replies', 'like', '%' . $request->reply_id . '%')->first();
        if ($review) {
            $replies = array_filter($review->replies, function($reply) use ($request) {
                return $reply['id'] !== $request->reply_id;
            });
            $review->replies = array_values($replies);
            $review->save();
            return back()->with('success', 'Reply deleted successfully.');
        }

        return back()->with('error', 'Reply not found.');
    }

    public function toggleFeatured(Request $request)
    {
        $request->validate([
            'review_id' => 'required|exists:reviews,id',
        ]);

        $review = Review::findOrFail($request->review_id);
        $review->featured = !$review->featured;
        $review->save();

        return back()->with('success', 'Review featured status updated.');
    }

    public function destroy(Request $request)
    {
        if ($request->has('review_id')) {
            $review = Review::findOrFail($request->review_id);
        } else {
            // Support for route parameter if any
            $reviewId = $request->route('review') ?? $request->route('id');
            $review = Review::findOrFail($reviewId);
        }
        
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }
}