@extends('admin.layouts.app')

@section('content')
<style>
    :root {
        --primary-color: #17320b;
        --secondary-color: #a8894d;
        --text-color: #374151;
        --bg-color: #f9fafb;
    }
    .card { background: white; border-radius: 0.75rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); padding: 1.5rem; border: 1px solid rgba(0,0,0,0.05); }
    .btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.625rem 1.25rem; border-radius: 0.5rem; font-weight: 600; transition: all 0.3s; cursor: pointer; border: 1px solid transparent; font-size: 0.875rem; }
    .btn-primary { background-color: var(--secondary-color); color: white; }
    .btn-primary:hover { background-color: #9d7c4f; transform: translateY(-1px); }
    .btn-outline { border-color: #e5e7eb; color: #374151; background: white; }
    .btn-outline:hover { background: #f9fafb; border-color: #d1d5db; }
    .form-input { width: 100%; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 0.625rem 0.875rem; transition: all 0.3s; font-size: 0.875rem; }
    .form-input:focus { outline: none; border-color: #a8894d; ring: 2px; }

    /* User aligned classes */
    .bg-background { background-color: white; }
    .border-secondary { border-color: var(--secondary-color); }
    .bg-secondary { background-color: var(--secondary-color); }
    .text-text { color: var(--primary-color); }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-[#17320b]">Manage Terms & Conditions</h2>
            <p class="text-gray-600">Update the Terms & Conditions content for your users</p>
        </div>
        <a href="{{ route('admin.account-content.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card">
                <h3 class="text-lg font-bold text-[#17320b] mb-4">Edit Terms & Conditions Content</h3>
                <form action="{{ route('admin.account-content.terms.update') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Page Title *</label>
                            <input type="text" name="title" value="{{ $terms->title }}" required class="form-input">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Content (Markdown/HTML supported) *</label>
                            <textarea name="content" rows="15" required class="w-full form-input" style="font-family: monospace;">{{ $terms->content }}</textarea>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-100">
                            <h4 class="text-md font-bold text-[#17320b] mb-3">SEO Settings</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                                    <input type="text" name="meta_title" value="{{ $terms->data['meta_title'] ?? '' }}" class="form-input" placeholder="Terms & Conditions | ZUBEEE">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                    <textarea name="meta_description" rows="2" class="form-input">{{ $terms->data['meta_description'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-full mt-6">
                            <i class="fas fa-save mr-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="card bg-[#17320b] text-white" style="background-color: #17320b !important;">
                <h3 class="text-lg font-bold text-[#a8894d] mb-3">T&C Guide</h3>
                <div class="text-sm space-y-3 opacity-90 text-white">
                    <p>This content is displayed to users when they click the "Terms & Conditions" link during Login or Signup.</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li>Use clear, legal language.</li>
                        <li>Update these whenever your policies change.</li>
                        <li>Format using basic HTML tags if needed.</li>
                    </ul>
                </div>
                <div class="mt-6 pt-4 border-t border-white/10">
                    <p class="text-xs text-gray-300">Last updated: {{ $terms->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>

            <div class="card">
                <h3 class="text-lg font-bold text-[#17320b] mb-3">Current Link</h3>
                <p class="text-sm text-gray-600 mb-4">Use this URL to link to the Terms & Conditions page:</p>
                <div class="bg-gray-50 p-3 rounded border border-gray-200 flex items-center justify-between">
                    <code class="text-xs text-secondary">/info/policy</code>
                    <button class="text-gray-400 hover:text-secondary" onclick="navigator.clipboard.writeText('/info/policy')">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <p class="text-[10px] text-gray-400 mt-2 italic">* Make sure this matches the URL in General Settings.</p>
            </div>
        </div>
    </div>
</div>
@endsection
