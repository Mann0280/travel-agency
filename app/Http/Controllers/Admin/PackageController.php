<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Destination;
use App\Models\Agency;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->has('ajax')) {
            return $this->listData($request);
        }

        $query = Package::with(['destination', 'agency']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
        }

        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('featured') && $request->featured !== null) {
            $query->where('is_featured', $request->featured == '1');
        }

        $packages = $query->latest()->paginate(10);
        return view('admin.packages.index', compact('packages'));
    }

    public function listData(Request $request)
    {
        $query = Package::with(['destination', 'agency']);

        // Handle Tabulator Filters
        if ($request->has('filters')) {
            $filters = $request->input('filters');
            foreach ($filters as $filter) {
                $field = $filter['field'];
                $value = $filter['value'];
                $type = $filter['type'];

                if ($field === 'name') {
                    $query->where('name', 'like', '%' . $value . '%');
                } elseif ($field === 'status') {
                    $query->where('status', $value);
                } elseif ($field === 'category') {
                    $query->where('category', $value);
                } elseif ($field === 'location') {
                    $query->where('location', 'like', '%' . $value . '%');
                }
            }
        }

        // Sorting
        if ($request->has('sorters')) {
            $sorters = $request->input('sorters');
            foreach ($sorters as $sorter) {
                $query->orderBy($sorter['field'], $sorter['dir']);
            }
        } else {
            $query->latest();
        }

        // Pagination
        $perPage = $request->input('size', 10);
        $page = $request->input('page', 1);
        
        $packages = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'last_page' => $packages->lastPage(),
            'data' => $packages->items(),
            'total' => $packages->total(),
        ]);
    }

    public function create()
    {
        $destinations = Destination::all();
        $agencies = Agency::all();
        return view('admin.packages.create', compact('destinations', 'agencies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'price' => 'nullable|numeric|min:0',
            'duration' => 'required|string|max:255',
            'from_city' => 'nullable|string|max:255',
            'departure_cities' => 'required|array|min:1',
            'departure_cities.*.city' => 'required|string',
            'departure_cities.*.price' => 'required|numeric|min:0',

            'duration_days' => 'required|integer|min:1',
            'agency_id' => 'required|exists:agencies,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'category' => 'required|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'is_featured' => 'boolean',
            'departure_cities' => 'nullable|array',
            'available_months' => 'nullable|array',
            'itinerary' => 'nullable|array',
            'inclusions' => 'nullable|array',
            'exclusions' => 'nullable|array',
            'things_to_carry' => 'nullable|array',
            'terms_conditions' => 'nullable|array',
            'contact_info' => 'required|array',
            'contact_info.email' => 'required|email',
            'contact_info.website' => 'required|url',
<<<<<<< HEAD
            'branches' => 'nullable|array',
            'branches.*.city' => 'required_with:branches|string',
            'branches.*.address' => 'required_with:branches|string',
            'branches.*.phone' => 'required_with:branches|string',
=======
>>>>>>> 9045ab82ddbea83eb9d0e2132c8aa2ebdfa5e4e0
            'destination_id' => 'required|exists:destinations,id',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_approved'] = true;
        
        // Ensure arrays are initialized if not present
        $data['departure_cities'] = $request->input('departure_cities', []);
        $data['available_months'] = $request->input('available_months', []);
        $data['itinerary'] = $request->input('itinerary', []);
        $data['inclusions'] = $request->input('inclusions', []);
        $data['exclusions'] = $request->input('exclusions', []);
        $data['things_to_carry'] = $request->input('things_to_carry', []);
        $data['terms_conditions'] = $request->input('terms_conditions', []);
        $data['contact_info'] = $request->input('contact_info', []);

        // Calculate minimum price from departure cities
        if (!empty($data['departure_cities'])) {
            $prices = array_column($data['departure_cities'], 'price');
            if (!empty($prices)) {
                $data['price'] = min($prices);
            }
        }


        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        Package::create($data);

        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully.');
    }

    public function edit(Package $package)
    {
        $destinations = Destination::all();
        $agencies = Agency::all();
        return view('admin.packages.edit', compact('package', 'destinations', 'agencies'));
    }

    public function update(Request $request, Package $package)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'image_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'price' => 'nullable|numeric|min:0',
            'duration' => 'required|string|max:255',
            'from_city' => 'nullable|string|max:255',
            'departure_cities' => 'required|array|min:1',
            'departure_cities.*.city' => 'required|string',
            'departure_cities.*.price' => 'required|numeric|min:0',

            'duration_days' => 'required|integer|min:1',
            'agency_id' => 'required|exists:agencies,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'category' => 'required|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'is_featured' => 'boolean',
            'departure_cities' => 'nullable|array',
            'available_months' => 'nullable|array',
            'itinerary' => 'nullable|array',
            'inclusions' => 'nullable|array',
            'exclusions' => 'nullable|array',
            'things_to_carry' => 'nullable|array',
            'terms_conditions' => 'nullable|array',
            'contact_info' => 'required|array',
            'contact_info.email' => 'required|email',
            'contact_info.website' => 'required|url',
<<<<<<< HEAD
            'branches' => 'nullable|array',
            'branches.*.city' => 'required_with:branches|string',
            'branches.*.address' => 'required_with:branches|string',
            'branches.*.phone' => 'required_with:branches|string',
=======
>>>>>>> 9045ab82ddbea83eb9d0e2132c8aa2ebdfa5e4e0
            'destination_id' => 'required|exists:destinations,id',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        
        // Ensure arrays are initialized if not present
        $data['departure_cities'] = $request->input('departure_cities', []);
        $data['available_months'] = $request->input('available_months', []);
        $data['itinerary'] = $request->input('itinerary', []);
        $data['inclusions'] = $request->input('inclusions', []);
        $data['exclusions'] = $request->input('exclusions', []);
        $data['things_to_carry'] = $request->input('things_to_carry', []);
        $data['terms_conditions'] = $request->input('terms_conditions', []);
        $data['contact_info'] = $request->input('contact_info', []);

        // Calculate minimum price from departure cities
        if (!empty($data['departure_cities'])) {
            $prices = array_column($data['departure_cities'], 'price');
            if (!empty($prices)) {
                $data['price'] = min($prices);
            }
        }


        if ($request->hasFile('image_upload')) {
            // Delete old image if it exists and is not a URL
            if ($package->image && !filter_var($package->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($package->image);
            }
            
            $data['image'] = $request->file('image_upload')->store('packages', 'public');
        }

        $package->update($data);

        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Package deleted successfully.');
    }

    public function toggleApproval(Package $package)
    {
        $package->update(['is_approved' => !$package->is_approved]);
        $status = $package->is_approved ? 'approved' : 'unapproved';
        return back()->with('success', "Package {$status} successfully.");
    }
}