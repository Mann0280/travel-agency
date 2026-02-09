<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageAgency;
use App\Models\Agency;
use Illuminate\Http\Request;

class PackageAgencyController extends Controller
{
    public function index(Package $package)
    {
        $package->load(['packageAgencies.agency']);
         // Filter agencies that are NOT already attached to this package for the dropdown
        $existingAgencyIds = $package->packageAgencies->pluck('agency_id')->toArray();
        $allAgencies = Agency::whereNotIn('id', $existingAgencyIds)->get();

        return view('admin.packages.agencies', compact('package', 'allAgencies'));
    }

    public function store(Request $request, Package $package)
    {
        $data = $request->validate([
            'agency_id' => 'required|exists:agencies,id',
            'price' => 'required|numeric|min:0',
            'commission' => 'required|numeric|min:0|max:100',
            'duration' => 'required|string|max:255',
            'date' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $package->packageAgencies()->create($data);

        return back()->with('success', 'Agency added to package successfully.');
    }

    public function update(Request $request, PackageAgency $packageAgency)
    {
        $data = $request->validate([
            'price' => 'required|numeric|min:0',
            'commission' => 'required|numeric|min:0|max:100',
            'duration' => 'required|string|max:255',
            'date' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $packageAgency->update($data);

        return back()->with('success', 'Agency updated successfully.');
    }

    public function destroy(PackageAgency $packageAgency)
    {
        $packageAgency->delete();
        return back()->with('success', 'Agency removed from package successfully.');
    }
}
