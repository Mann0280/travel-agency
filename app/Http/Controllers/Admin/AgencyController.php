<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function index(Request $request)
    {
        $query = Agency::query();

        // Search logic
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // State filter
        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        // Experience filter
        if ($request->filled('experience')) {
            $exp = $request->experience;
            if ($exp === '16+') {
                $query->where('experience_years', '>=', 16);
            } else {
                $range = explode('-', $exp);
                if (count($range) === 2) {
                    $query->whereBetween('experience_years', [$range[0], $range[1]]);
                }
            }
        }

        $agencies = $query->withCount('packages')->latest()->paginate(10);
        
        // Get unique states for the filter
        $states = Agency::whereNotNull('state')->distinct()->pluck('state')->sort();

        return view('admin.agencies.index', compact('agencies', 'states'));
    }

    public function create()
    {
        return view('admin.agencies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:agencies',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'description' => 'required|string',
            'contact_person' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'logo_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'status' => 'nullable|string|in:active,inactive,pending',
        ]);

        if ($request->hasFile('logo_upload')) {
            $path = $request->file('logo_upload')->store('agencies/logos', 'public');
            $validated['logo'] = $path;
        }

        // Add default password for new agencies if not provided or just handle it from form
        $validated['password'] = bcrypt($request->password);

        Agency::create(array_merge($request->all(), $validated));

        return redirect()->route('admin.agencies.index')->with('success', 'Agency created successfully.');
    }

    public function show(Agency $agency)
    {
        $agency->loadCount('packages');
        return view('admin.agencies.show', compact('agency'));
    }

    public function edit(Agency $agency)
    {
        return view('admin.agencies.edit', compact('agency'));
    }

    public function update(Request $request, Agency $agency)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:agencies,email,' . $agency->id,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'description' => 'required|string',
            'contact_person' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'logo_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'status' => 'nullable|string|in:active,inactive,pending',
        ]);

        if ($request->hasFile('logo_upload')) {
            $path = $request->file('logo_upload')->store('agencies/logos', 'public');
            $validated['logo'] = $path;
        }

        if (!empty($request->password)) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
        }

        $agency->update(array_merge($request->all(), $validated));

        return redirect()->route('admin.agencies.index')->with('success', 'Agency updated successfully.');
    }

    public function destroy(Agency $agency)
    {
        $agency->delete();
        return redirect()->route('admin.agencies.index')->with('success', 'Agency deleted successfully.');
    }

    public function impersonate(Agency $agency)
    {
        auth()->guard('agency')->login($agency);
        return redirect()->route('agency.dashboard')->with('success', 'You are now logged in as ' . $agency->name);
    }

    public function stopImpersonating()
    {
        auth()->guard('agency')->logout();
        return redirect()->route('admin.agencies.index')->with('success', 'You have stopped impersonating.');
    }
}