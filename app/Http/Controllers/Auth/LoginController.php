<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        // If user is already logged in, redirect to home
        if (Auth::check()) {
            return redirect()->route('home');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Validate the request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to log the user in
        $remember = $request->has('remember_me');
        
        if (Auth::attempt($credentials, $remember)) {
            // Update last login time
            Auth::user()->update(['last_login' => now()]);
            
            // Regenerate session to prevent fixation attacks
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        // If authentication fails, redirect back with error
        return back()->withErrors([
            'email' => 'Invalid email or password. Please try again.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        // Isolated logout: only logout 'web' guard
        Auth::guard('web')->logout();
        
        // Do NOT invalidate session or regenerate token to keep other guards active

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
