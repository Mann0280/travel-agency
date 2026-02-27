<?php
// app/Http/Controllers/AccountController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountContent;
use App\Models\Feedback;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access your account');
        }

        $user = Auth::user();
        
        // Load partner application and agency relationships
        $user->load(['partnerApplication', 'agency']);

        // Get current tab from request or default to profile
        $currentTab = $request->input('tab', 'profile');
        
        // Fetch real bookings from database
        $rawBookings = $user->bookings()->with('package')->latest()->take(5)->get();
        
        $bookings = $rawBookings->map(function($booking) {
            return [
                'id' => 'ZB' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
                'title' => $booking->package->name ?? 'Package Deleted',
                'status' => ucfirst($booking->status),
                'status_class' => $this->getStatusClass($booking->status),
                'duration' => $booking->package->duration ?? 'N/A',
                'travel_date' => $booking->travel_date ? $booking->travel_date->format('M d, Y') : 'Flexible',
                'travelers' => $booking->travellers . ' Adults',
                'total' => '₹' . number_format($booking->total_amount, 2)
            ];
        });

        // Fetch dynamic content
        $faqs = \App\Models\Faq::where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        $about = AccountContent::where('slug', 'about')->first();
        $aboutData = $about ? $about->data : [];

        $partner = AccountContent::where('slug', 'partner')->first();
        $partnerData = $partner ? $partner->data : [];
            
        $policy = AccountContent::where('type', 'policy')->first(); // Keeping fallback for legacy if needed/mixed
        $policyContent = $policy ? $policy->data : [];

        $feedbackCategories = \App\Models\FeedbackCategory::orderBy('label')->get();

        return view('user.account', compact('currentTab', 'bookings', 'rawBookings', 'faqs', 'aboutData', 'partnerData', 'policyContent', 'feedbackCategories', 'user'));
    }

    private function getStatusClass($status)
    {
        switch (strtolower($status)) {
            case 'confirmed': return 'bg-green-800 text-green-100';
            case 'pending': return 'bg-yellow-800 text-yellow-100';
            case 'completed': return 'bg-blue-800 text-blue-100';
            case 'cancelled': return 'bg-red-800 text-red-100';
            default: return 'bg-gray-800 text-gray-100';
        }
    }

    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20'
        ]);

        // Update user profile
        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }

    public function changePassword(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        // Validate input
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed'
        ]);

        // Check if current password matches
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password does not match nuestro record.'
            ], 422);
        }

        // Change password
        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }

    public function submitFeedback(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validate input
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'category' => 'required|array|min:1',
            'category.*' => 'string',
            'feedback' => 'required|string|max:1000',
            'recommend' => 'required|string|in:yes,no,maybe'
        ]);

        // Save feedback
        Feedback::create([
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'category' => implode(', ', $validated['category']),
            'message' => $validated['feedback'],
            'recommend' => $validated['recommend']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback submitted successfully'
        ]);
    }

    public function submitPartnerApplication(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'message' => 'required|string|max:1000'
        ]);

        // Save partner application (in real app)
        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully! We will contact you within 2 business days.'
        ]);
    }

    public function downloadInvoice($bookingId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to download invoice');
        }

        $user = Auth::user();
        $booking = $user->bookings()->with('package')->findOrFail($bookingId);

        // Check if PDF package is installed
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return back()->with('error', 'PDF package not installed. Please run: composer require barryvdh/laravel-dompdf');
        }

        // Load PDF library
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user.invoice', compact('booking'));
        
        $invoiceNumber = 'INV-ZB' . str_pad($booking->id, 6, '0', STR_PAD_LEFT);
        
        return $pdf->download($invoiceNumber . '.pdf');
    }
}