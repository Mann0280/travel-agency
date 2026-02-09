<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AgencyBaseController extends Controller
{
    /**
     * Get the currently active agency (either owned or impersonated).
     */
    protected function getActiveAgency()
    {
        // 1. Check for impersonation session (only if user is admin)
        if (Auth::guard('admin')->check() && session()->has('impersonate_agency_id')) {
            $agency = Agency::find(session('impersonate_agency_id'));
            if ($agency) return $agency;
        }

        // 2. Check for authenticated agency guard
        if (Auth::guard('agency')->check()) {
            return Auth::guard('agency')->user();
        }

        // 3. Fallback for admins: Redirect to agency index to pick one
        if (Auth::guard('admin')->check()) {
            abort(redirect()->route('admin.agencies.index')->with('error', 'Please select an agency to manage.'));
        }

        // 4. Ultimate fallback (should be caught by middleware)
        abort(403, 'Unauthorized access.');
    }
}
