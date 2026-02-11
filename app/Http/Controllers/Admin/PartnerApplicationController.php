<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerApplication;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PartnerApplicationController extends Controller
{
    /**
     * Display a listing of partner applications
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = PartnerApplication::with(['user', 'reviewer'])->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $applications = $query->paginate(10);

        // Get stats
        $stats = [
            'total' => PartnerApplication::count(),
            'pending' => PartnerApplication::pending()->count(),
            'approved' => PartnerApplication::approved()->count(),
            'rejected' => PartnerApplication::rejected()->count(),
        ];

        return view('admin.partner-applications.index', compact('applications', 'stats', 'status'));
    }

    /**
     * Display the specified application
     */
    public function show(PartnerApplication $application)
    {
        $application->load(['user', 'reviewer']);
        return view('admin.partner-applications.show', compact('application'));
    }

    /**
     * Approve a partner application
     */
    public function approve(Request $request, PartnerApplication $application)
    {
        if ($application->status !== 'pending') {
            return back()->with('error', 'This application has already been reviewed.');
        }

        try {
            DB::beginTransaction();

            // Create the agency
            $agency = Agency::create([
                'name' => $application->agency_name,
                'email' => $application->business_email,
                'password' => $application->user->password, // Copy user password
                'phone' => $application->phone,
                'address' => $application->address,
                'description' => $application->description,
                'status' => 'active',
            ]);

            // Link user to agency and update role
            $application->user->update([
                'agency_id' => $agency->id,
                'role' => 'agency',
            ]);

            // Update application status
            $application->update([
                'status' => 'approved',
                'reviewed_by' => Auth::guard('admin')->id(),
                'reviewed_at' => now(),
                'admin_notes' => $request->input('notes'),
            ]);

            DB::commit();

            return redirect()->route('admin.partner-applications.index')
                ->with('success', 'Application approved successfully! Agency has been created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve application: ' . $e->getMessage());
        }
    }

    /**
     * Reject a partner application
     */
    public function reject(Request $request, PartnerApplication $application)
    {
        if ($application->status !== 'pending') {
            return back()->with('error', 'This application has already been reviewed.');
        }

        $request->validate([
            'reason' => 'required|string|min:10',
        ]);

        $application->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::guard('admin')->id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->input('reason'),
        ]);

        return redirect()->route('admin.partner-applications.index')
            ->with('success', 'Application rejected.');
    }
}
