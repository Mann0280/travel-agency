<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AuthHelper;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        // If user is already logged in, redirect to account page
        if (Auth::guard('web')->check()) {
            return redirect()->route('account');
        }

        return view('user.auth.login');
    }

    // Handle login request
    public function login(Request $request)
    {
        // Validate the request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Add remember me functionality
        $remember = $request->has('remember_me');

        // Check if authentication succeeds
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            // Regenerate session
            $request->session()->regenerate();
            
            // Redirect to specifically requested package if session flag exists
            if ($request->session()->has('redirect_package')) {
                $packageId = $request->session()->pull('redirect_package');
                return redirect()->route('package.show', $packageId)->with('login_success', 'Welcome back! You can now view the package details.');
            }
            
            return redirect()->intended(route('account'))->with('login_success', 'Welcome back! You have successfully logged in.');
        }

        // If authentication fails
        return back()->withErrors([
            'email' => 'Invalid email or password. Please try again.',
        ])->withInput($request->except('password'));
    }

    // Show registration form
    public function showRegister()
    {
        // If user is already logged in, redirect to account page
        if (Auth::guard('web')->check()) {
            return redirect()->route('account');
        }

        return view('user.auth.register');
    }

    // Handle registration request
    public function register(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
        ]);

        // Log in the user
        Auth::guard('web')->login($user);

        // Redirect to specifically requested package if session flag exists
        if ($request->session()->has('redirect_package')) {
            $packageId = $request->session()->pull('redirect_package');
            return redirect()->route('package.show', $packageId)->with('success', 'Registration successful! You can now view the package details.');
        }

        // Redirect to account
        return redirect()->route('account')->with('success', 'Registration successful! Welcome to ZUBEEE.');
    }

    // Handle logout
    public function logout(Request $request)
    {
        // Isolated logout: only logout 'web' guard
        Auth::guard('web')->logout();
        
        // Do NOT invalidate session or regenerate token to keep other guards active
        
        return redirect()->route('home');
    }

    // Demo user method (for demo purposes only)
    private function getOrCreateDemoUser()
    {
        // Check if demo user exists
        $user = User::where('email', 'demo@zubeee.com')->first();
        
        if (!$user) {
            // Create demo user (in real app, you'd hash the password)
            $user = User::create([
                'name' => 'Demo User',
                'email' => 'demo@zubeee.com',
                'password' => bcrypt('password'), // In real app, use Hash::make()
                'phone' => '+1 234 567 8900',
            ]);
        }
        
        return $user;
    }
}