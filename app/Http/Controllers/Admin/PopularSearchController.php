<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PopularSearch;
use Illuminate\Http\Request;

class PopularSearchController extends Controller
{
    public function index()
    {
        $searches = PopularSearch::ordered()->get();
        
        // Calculate stats
        $totalSearches = $searches->count();
        $activeSearches = $searches->where('status', 'active')->count();
        $inactiveSearches = $totalSearches - $activeSearches;
        $totalClicks = $searches->sum('clicks');
        
        return view('admin.popular-searches.index', compact(
            'searches',
            'totalSearches',
            'activeSearches',
            'inactiveSearches',
            'totalClicks'
        ));
    }

    public function create()
    {
        $nextOrder = PopularSearch::max('order') + 1;
        return view('admin.popular-searches.create', compact('nextOrder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_city' => 'nullable|string|max:255',
            'to_city' => 'nullable|string|max:255',
            'display_text' => 'nullable|string|max:50',
            'order' => 'required|integer|min:1',
        ], [
            'order.required' => 'Display order is required',
            'order.min' => 'Order must be at least 1',
        ]);

        // Validate that at least one city is filled
        if (empty($request->from_city) && empty($request->to_city)) {
            return back()->withErrors(['from_city' => 'Please fill either "From" or "To" (or both)'])->withInput();
        }

        // Check for duplicates
        $exists = PopularSearch::where('from_city', $request->from_city)
            ->where('to_city', $request->to_city)
            ->exists();

        if ($exists) {
            return back()->withErrors(['from_city' => 'A search with this exact "From" and "To" combination already exists'])->withInput();
        }

        // Auto-generate display text if not provided
        $displayText = PopularSearch::generateDisplayText(
            $request->from_city,
            $request->to_city,
            $request->display_text
        );

        PopularSearch::create([
            'from_city' => $request->from_city,
            'to_city' => $request->to_city,
            'display_text' => $displayText,
            'status' => 'active',
            'order' => $request->order,
            'clicks' => 0,
        ]);

        return redirect()->route('admin.popular-searches.index')
            ->with('success', 'Popular search added successfully!');
    }

    public function edit(PopularSearch $popularSearch)
    {
        return view('admin.popular-searches.edit', compact('popularSearch'));
    }

    public function update(Request $request, PopularSearch $popularSearch)
    {
        $request->validate([
            'from_city' => 'nullable|string|max:255',
            'to_city' => 'nullable|string|max:255',
            'display_text' => 'nullable|string|max:50',
            'order' => 'required|integer|min:1',
        ]);

        // Validate that at least one city is filled
        if (empty($request->from_city) && empty($request->to_city)) {
            return back()->withErrors(['from_city' => 'Please fill either "From" or "To" (or both)'])->withInput();
        }

        // Check for duplicates (excluding current search)
        $exists = PopularSearch::where('from_city', $request->from_city)
            ->where('to_city', $request->to_city)
            ->where('id', '!=', $popularSearch->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['from_city' => 'A search with this exact "From" and "To" combination already exists'])->withInput();
        }

        // Auto-generate display text if not provided
        $displayText = PopularSearch::generateDisplayText(
            $request->from_city,
            $request->to_city,
            $request->display_text
        );

        $popularSearch->update([
            'from_city' => $request->from_city,
            'to_city' => $request->to_city,
            'display_text' => $displayText,
            'order' => $request->order,
        ]);

        return redirect()->route('admin.popular-searches.index')
            ->with('success', 'Search updated successfully!');
    }

    public function destroy(PopularSearch $popularSearch)
    {
        $popularSearch->delete();
        return redirect()->route('admin.popular-searches.index')
            ->with('success', 'Popular search deleted successfully!');
    }

    public function toggleStatus(PopularSearch $popularSearch)
    {
        $popularSearch->update([
            'status' => $popularSearch->status === 'active' ? 'inactive' : 'active'
        ]);

        return back()->with('success', 'Search status updated successfully!');
    }

    public function resetClicks(PopularSearch $popularSearch)
    {
        $popularSearch->update(['clicks' => 0]);
        return back()->with('success', 'Click count reset to zero!');
    }
}