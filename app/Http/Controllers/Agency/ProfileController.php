<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends AgencyBaseController
{
    public function edit()
    {
        $agency = $this->getActiveAgency();
        return view('agency.profile.edit', compact('agency'));
    }

    public function update(Request $request)
    {
        $agency = $this->getActiveAgency();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($agency->logo) {
                Storage::disk('public')->delete($agency->logo);
            }
            $data['logo'] = $request->file('logo')->store('agencies', 'public');
        }

        $agency->update($data);

        return redirect()->route('agency.profile.edit')->with('success', 'Agency profile updated successfully.');
    }
}
