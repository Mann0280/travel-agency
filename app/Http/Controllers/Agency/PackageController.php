<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Destination;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PackageController extends AgencyBaseController
{
    public function index()
    {
        $agency = $this->getActiveAgency();
        $packages = Package::with(['destination'])
            ->where('agency_id', $agency->id)
            ->paginate(10);
        return view('agency.packages.index', compact('packages'));
    }

    public function create()
    {
        $destinations = Destination::all();
        return view('agency.packages.create', compact('destinations'));
    }

    public function store(Request $request)
    {
        $agency = $this->getActiveAgency();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:255',
            'departure_cities' => 'required|array',
            'travel_date' => 'required|date',
            'is_featured' => 'boolean',
            'destination_id' => 'required|exists:destinations,id',
            'itinerary' => 'nullable|array',
            'inclusions' => 'nullable|array',
            'exclusions' => 'nullable|array',
            'things_to_carry' => 'nullable|array',
            'terms_conditions' => 'nullable|array',
        ]);

        $data['agency_id'] = $agency->id;
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_approved'] = true; // Automatically approved as per user request

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        Package::create($data);

        return redirect()->route('agency.packages.index')->with('success', 'Package created successfully. It will be visible after admin approval.');
    }

    public function edit(Package $package)
    {
        $agency = $this->getActiveAgency();
        if ($package->agency_id !== $agency->id) {
            abort(403);
        }
        $destinations = Destination::all();
        return view('agency.packages.edit', compact('package', 'destinations'));
    }

    public function update(Request $request, Package $package)
    {
        $agency = $this->getActiveAgency();
        if ($package->agency_id !== $agency->id) {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:255',
            'departure_cities' => 'required|array',
            'travel_date' => 'required|date',
            'is_featured' => 'boolean',
            'destination_id' => 'required|exists:destinations,id',
            'itinerary' => 'nullable|array',
            'inclusions' => 'nullable|array',
            'exclusions' => 'nullable|array',
            'things_to_carry' => 'nullable|array',
            'terms_conditions' => 'nullable|array',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_approved'] = true; // Automatically approved as per user request

        if ($request->hasFile('image_upload')) {
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
            }
            $data['image'] = $request->file('image_upload')->store('packages', 'public');
        }

        $package->update($data);

        return redirect()->route('agency.packages.index')->with('success', 'Package updated successfully. Admin will review the changes.');
    }

    public function destroy(Package $package)
    {
        $agency = $this->getActiveAgency();
        if ($package->agency_id !== $agency->id) {
            abort(403);
        }

        // Check for active bookings
        if ($package->bookings()->count() > 0) {
            return redirect()->route('agency.packages.index')->with('error', 'Cannot delete package with active bookings.');
        }
        
        if ($package->image) {
            Storage::disk('public')->delete($package->image);
        }
        
        $package->delete();
        return redirect()->route('agency.packages.index')->with('success', 'Package deleted successfully.');
    }
}
