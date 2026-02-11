<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PartnerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerApplicationController extends Controller
{
    /**
     * Store a new partner application
     */
    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Partner Application Submission Attempt', [
            'user_id' => Auth::id(),
            'data' => $request->except(['password', '_token'])
        ]);

        $user = Auth::user();

        // Check if user already has an agency
        if ($user->agency_id) {
            \Illuminate\Support\Facades\Log::warning('Partner App Failed: User already has agency', ['user_id' => $user->id]);
            return back()->with('error', 'You are already a partner agency.');
        }

        // Check if user has a pending application
        if ($user->hasPendingApplication()) {
            \Illuminate\Support\Facades\Log::warning('Partner App Failed: User has pending app', ['user_id' => $user->id]);
            return back()->with('error', 'You already have a pending application.');
        }

        // Check if user has an approved application
        if ($user->partnerApplication()->where('status', 'approved')->exists()) {
            \Illuminate\Support\Facades\Log::warning('Partner App Failed: User has approved app', ['user_id' => $user->id]);
            return back()->with('error', 'You already have an approved application.');
        }

        // Validate the request
        try {
            $validated = $request->validate([
                'agency_name' => 'required|string|max:255',
                'business_email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'description' => 'required|string|min:10',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Partner App Validation Failed', [
                'user_id' => $user->id,
                'errors' => $e->errors()
            ]);
            throw $e;
        }

        // Create the application
        try {
            $application = PartnerApplication::create([
                'user_id' => $user->id,
                'agency_name' => $validated['agency_name'],
                'business_email' => $validated['business_email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'description' => $validated['description'],
                'status' => 'pending',
            ]);

            \Illuminate\Support\Facades\Log::info('Partner Application Created Successfully', ['app_id' => $application->id]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Partner App Database Creation Failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Something went wrong. Please try again later.');
        }

        return back()->with('success', 'Your partner application has been submitted successfully! We will review it shortly.');
    }
}
