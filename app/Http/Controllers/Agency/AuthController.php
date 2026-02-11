<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('agency')->check()) {
            return redirect()->route('agency.dashboard');
        }

        if (Auth::guard('admin')->check()) {
            if (session()->has('impersonate_agency_id')) {
                return redirect()->route('agency.dashboard');
            }
            return redirect()->route('admin.agencies.index')->with('info', 'Please select an agency to manage in the Agency Panel.');
        }

        return view('agency.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        \Illuminate\Support\Facades\Log::info('Agency Login Attempt', ['email' => $credentials['email']]);

        // Debug: Check user existence and hash
        $agency = \App\Models\Agency::where('email', $credentials['email'])->first();
        if ($agency) {
            \Illuminate\Support\Facades\Log::info('Agency found', ['id' => $agency->id, 'email' => $agency->email]);
            if (\Illuminate\Support\Facades\Hash::check($credentials['password'], $agency->password)) {
                \Illuminate\Support\Facades\Log::info('Password hash MATCHES manually');
            } else {
                \Illuminate\Support\Facades\Log::error('Password hash DOES NOT MATCH manually');
            }
        } else {
            \Illuminate\Support\Facades\Log::error('Agency NOT found by email');
        }

        if (Auth::guard('agency')->attempt($credentials, $request->boolean('remember'))) {
            \Illuminate\Support\Facades\Log::info('Auth::guard(agency)->attempt SUCCEEDED');
            $request->session()->regenerate();
            return redirect()->intended(route('agency.dashboard'));
        }

        \Illuminate\Support\Facades\Log::error('Auth::guard(agency)->attempt FAILED');
        return back()->with('error', 'The provided credentials do not match our records.')->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // If admin was impersonating, just clear the session and go back to admin
        if ($request->session()->has('impersonate_agency_id')) {
            $request->session()->forget('impersonate_agency_id');
            return redirect()->route('admin.agencies.index')->with('success', 'Ended agency session.');
        }

        // Isolated logout: only logout 'agency' guard
        Auth::guard('agency')->logout();
        
        // Do NOT invalidate session or regenerate token to keep other guards active

        return redirect()->route('agency.login');
    }
}
