<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->get();
        
        // Calculate stats
        $totalBanners = $banners->count();
        $activeBanners = $banners->where('is_active', true)->count();
        $inactiveBanners = $totalBanners - $activeBanners;
        
        return view('admin.banner.index', compact(
            'banners',
            'totalBanners',
            'activeBanners',
            'inactiveBanners'
        ));
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|max:2048',
            'link' => 'nullable|url',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($data);

        return redirect()->route('admin.banner.index')->with('success', 'Banner created successfully.');
    }

    public function show(Banner $banner)
    {
        return view('admin.banner.show', compact('banner'));
    }

    public function edit(Banner $banner)
    {
        return view('admin.banner.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'link' => 'nullable|url',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($banner->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banner.index')->with('success', 'Banner updated successfully.');
    }


    public function toggleStatus(Banner $banner)
    {
        $banner->update([
            'is_active' => !$banner->is_active
        ]);
        
        return redirect()->route('admin.banner.index')->with('success', 'Banner status updated successfully!');
    }

    public function destroy(Banner $banner)
    {
        // Delete image from storage
        if ($banner->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($banner->image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();
        return redirect()->route('admin.banner.index')->with('success', 'Banner deleted successfully.');
    }
}