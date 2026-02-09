<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        
        // Calculate stats
        $totalUsers = $users->count();
        $activeUsers = $users->where('status', 'active')->count();
        $totalBookings = 0; // Will be calculated when bookings exist
        $totalRevenue = 0; // Will be calculated when bookings exist
        
        return view('admin.users.index', compact('users', 'totalUsers', 'activeUsers', 'totalBookings', 'totalRevenue'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'role' => 'required|in:user,agent,admin',
            'status' => 'required|in:active,inactive,pending',
            'budget_range' => 'nullable|string',
            'preferred_duration' => 'nullable|string',
            'emergency_name' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'emergency_relationship' => 'nullable|string|max:100',
        ]);

        // Handle preferences array
        $preferences = $request->input('preferences', []);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'address_line1' => $validated['address_line1'] ?? null,
            'address_line2' => $validated['address_line2'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'country' => $validated['country'] ?? 'India',
            'pincode' => $validated['pincode'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'],
            'preferences' => $preferences,
            'budget_range' => $validated['budget_range'] ?? null,
            'preferred_duration' => $validated['preferred_duration'] ?? null,
            'emergency_name' => $validated['emergency_name'] ?? null,
            'emergency_phone' => $validated['emergency_phone'] ?? null,
            'emergency_relationship' => $validated['emergency_relationship'] ?? null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $bookingsCount = 0; // Will be calculated when bookings exist
        $totalSpent = 0; // Will be calculated when bookings exist
        $wishlistCount = 0; // Will be calculated when wishlist exists
        
        return view('admin.users.edit', compact('user', 'bookingsCount', 'totalSpent', 'wishlistCount'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'role' => 'required|in:user,agent,admin',
            'status' => 'required|in:active,inactive,pending',
            'budget_range' => 'nullable|string',
            'preferred_duration' => 'nullable|string',
            'emergency_name' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'emergency_relationship' => 'nullable|string|max:100',
        ]);

        // Handle preferences array
        $preferences = $request->input('preferences', []);
        
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'address_line1' => $validated['address_line1'] ?? null,
            'address_line2' => $validated['address_line2'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'country' => $validated['country'] ?? 'India',
            'pincode' => $validated['pincode'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'],
            'preferences' => $preferences,
            'budget_range' => $validated['budget_range'] ?? null,
            'preferred_duration' => $validated['preferred_duration'] ?? null,
            'emergency_name' => $validated['emergency_name'] ?? null,
            'emergency_phone' => $validated['emergency_phone'] ?? null,
            'emergency_relationship' => $validated['emergency_relationship'] ?? null,
        ];

        // Handle password update if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $updateData['password'] = Hash::make($request->input('password'));
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}