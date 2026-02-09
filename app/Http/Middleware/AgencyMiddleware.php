<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgencyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Allow admins to access agency panel if they are impersonating
        if (Auth::guard('admin')->check() && $request->session()->has('impersonate_agency_id')) {
            return $next($request);
        }

        if (!Auth::guard('agency')->check()) {
            return redirect()->route('agency.login')->with('error', 'Please login to access the Agency Panel.');
        }

        return $next($request);
    }
}
