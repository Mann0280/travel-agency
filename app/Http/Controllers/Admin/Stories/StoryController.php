<?php

namespace App\Http\Controllers\Admin\Stories;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    public function index()
    {
        $stories = Story::with('package')->orderBy('order', 'asc')->get();
        
        // Calculate stats
        $totalStories = $stories->count();
        $totalViews = $stories->sum('views');
        $totalClicks = $stories->sum('clicks');
        $featuredCount = $stories->where('is_featured', true)->count();
        
        return view('admin.stories.index', compact(
            'stories',
            'totalStories',
            'totalViews',
            'totalClicks',
            'featuredCount'
        ));
    }

    public function create()
    {
        $packages = \App\Models\Package::orderBy('name')->get();
        return view('admin.stories.add', compact('packages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'destination' => 'required|string|max:255',
            'date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'items' => 'nullable|array',
            'items.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:20480', // 20MB max for video
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer',
            'package_id' => 'nullable|exists:packages,id',
            'views' => 'nullable|integer|min:0',
            'clicks' => 'nullable|integer|min:0',
        ]);

        $data['title'] = $request->input('title', $data['destination']);
        $data['content'] = $request->input('content', 'Magic moments from ' . $data['destination']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);
        $data['order'] = $request->input('order', 0);
        $data['user_id'] = null;
        $data['package_id'] = $request->input('package_id');
        $data['views'] = $request->input('views', 0);
        $data['clicks'] = $request->input('clicks', 0);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('stories', 'public');
        }

        $story = Story::create($data);

        // Handle additional items
        if ($request->hasFile('items')) {
            foreach ($request->file('items') as $index => $file) {
                $path = $file->store('stories/items', 'public');
                $type = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';
                
                $story->items()->create([
                    'file_path' => $path,
                    'type' => $type,
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.stories.index')->with('success', 'Story added successfully.');
    }

    public function edit(Story $story)
    {
        $packages = \App\Models\Package::orderBy('name')->get();
        return view('admin.stories.edit', compact('story', 'packages'));
    }

    public function update(Request $request, Story $story)
    {
        $data = $request->validate([
            'destination' => 'required|string|max:255',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'items' => 'nullable|array',
            'items.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:20480',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer',
            'package_id' => 'nullable|exists:packages,id',
            'views' => 'nullable|integer|min:0',
            'clicks' => 'nullable|integer|min:0',
        ]);

        $data['title'] = $request->input('title', $data['destination']);
        $data['content'] = $request->input('content', $story->content);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured', $story->is_featured);
        $data['order'] = $request->input('order', $story->order);
        $data['package_id'] = $request->input('package_id');
        $data['views'] = $request->input('views', $story->views);
        $data['clicks'] = $request->input('clicks', $story->clicks);

        if ($request->hasFile('image')) {
            if ($story->image) {
                Storage::disk('public')->delete($story->image);
            }
            $data['image'] = $request->file('image')->store('stories', 'public');
        }

        $story->update($data);

        // Handle additional items
        if ($request->hasFile('items')) {
            $lastOrder = $story->items()->max('order') ?? -1;
            foreach ($request->file('items') as $index => $file) {
                $path = $file->store('stories/items', 'public');
                $type = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';
                
                $story->items()->create([
                    'file_path' => $path,
                    'type' => $type,
                    'order' => $lastOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.stories.index')->with('success', 'Story updated successfully.');
    }

    public function deleteItem($id)
    {
        $item = \App\Models\StoryItem::findOrFail($id);
        Storage::disk('public')->delete($item->file_path);
        $item->delete();

        return response()->json(['success' => true]);
    }

    public function destroy(Story $story)
    {
        if ($story->image) {
            Storage::disk('public')->delete($story->image);
        }

        // Delete all items
        foreach ($story->items as $item) {
            Storage::disk('public')->delete($item->file_path);
        }
        
        $story->delete();

        return redirect()->route('admin.stories.index')->with('success', 'Story deleted successfully.');
    }

    public function toggleStatus(Story $story)
    {
        $story->update(['is_active' => !$story->is_active]);
        return back()->with('success', 'Story status updated successfully.');
    }
}
