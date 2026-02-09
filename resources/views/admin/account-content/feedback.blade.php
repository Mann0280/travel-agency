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
    .form-input:focus { outline: none; border-color: #a8894d; }

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
            <h2 class="text-2xl font-bold text-[#17320b]">Manage Feedback Form</h2>
            <p class="text-gray-600">Configure feedback categories and default text</p>
        </div>
        <a href="{{ route('admin.account-content.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>

    <!-- Category Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-[#17320b]">Feedback Categories</h3>
                <button onclick="document.getElementById('addCategoryModal').classList.remove('hidden')" class="btn btn-primary btn-sm px-3 py-1.5 text-xs text-white">
                    <i class="fas fa-plus mr-1"></i> Add Category
                </button>
            </div>
            <div class="space-y-3">
                @forelse ($categories as $category)
                    <div class="flex items-center justify-between p-3 bg-gray-50/50 rounded-xl border border-gray-100">
                        <div class="text-sm font-bold text-[#17320b]">{{ $category->label }}</div>
                        <div class="flex space-x-3">
                            <button onclick="editCategory(@json($category))" class="text-secondary hover:text-[#9d7c4f] transition"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.account-content.feedback.category.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category?')">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-700 transition"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-400">No categories found.</div>
                @endforelse
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-bold text-[#17320b] mb-4">Feedback Content Settings</h3>
            <form action="{{ route('admin.account-content.feedback.settings') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Default Feedback (Placeholder) *</label>
                        <textarea name="default_feedback" rows="4" required class="w-full form-input">{{ $feedbackData['default_feedback'] ?? '' }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Save Settings</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Mobile Preview -->
    <div class="card">
        <h3 class="text-lg font-bold text-text mb-4">Mobile Preview</h3>
        <p class="text-gray-600 mb-4">This is how the Feedback form will appear:</p>
        
        <div class="bg-background border border-secondary rounded-lg p-4 max-w-sm mx-auto shadow-sm">
            <!-- Tabs Preview -->
            <div class="flex overflow-x-auto mb-4 pb-2">
                <div class="flex space-x-2">
                    <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full border border-secondary text-text">Profile</span>
                    <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full bg-secondary text-white">Feedback</span>
                    <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full border border-secondary text-text">Help</span>
                    <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full border border-secondary text-text">About</span>
                </div>
            </div>
            
            <div class="space-y-5">
                <div class="text-center">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Rate Us</p>
                    <div class="flex justify-center space-x-2">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fas fa-star text-lg text-secondary"></i>
                        @endFor
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-text mb-2 uppercase">Category</label>
                    <div class="w-full bg-gray-50 border border-gray-100 rounded-lg p-3 flex justify-between items-center text-xs text-gray-400">
                        <span>Select Category</span>
                        <i class="fas fa-chevron-down text-[10px] text-secondary"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-text mb-2 uppercase">Message</label>
                    <div class="w-full bg-gray-50 border border-gray-100 rounded-lg p-3 text-xs text-gray-400 h-24 italic">
                        {{ Str::limit($feedbackData['default_feedback'] ?? 'Share your experience...', 100) }}
                    </div>
                </div>

                <button class="w-full bg-[#17320b] text-white py-3 rounded-lg text-xs font-bold uppercase tracking-widest shadow-md pt-3">
                    Submit Feedback
                </button>
            </div>
        </div>
        
        <div class="text-xs text-gray-500 mt-6 text-center italic">
            <i class="fas fa-info-circle mr-1"></i> Live preview of the feedback portal layout.
        </div>
    </div>
</div>

<!-- Modals -->
<div id="addCategoryModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 p-6 border-t-4 border-secondary">
        <h3 class="text-lg font-bold text-[#17320b] mb-4">Add Category</h3>
        <form action="{{ route('admin.account-content.feedback.category.store') }}" method="POST" class="space-y-4">
            @csrf
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Key (e.g. app-design)</label><input type="text" name="key" required class="form-input"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Label (e.g. App Design)</label><input type="text" name="label" required class="form-input"></div>
            <div class="flex space-x-3 pt-2">
                <button type="submit" class="btn btn-primary flex-1">Create</button>
                <button type="button" onclick="document.getElementById('addCategoryModal').classList.add('hidden')" class="btn btn-outline flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div id="editCategoryModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 p-6 border-t-4 border-secondary">
        <h3 class="text-lg font-bold text-[#17320b] mb-4">Edit Category</h3>
        <form id="editCategoryForm" method="POST" class="space-y-4">
            @csrf
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Label</label><input type="text" name="label" id="editLabel" required class="form-input"></div>
            <div class="flex space-x-3 pt-2">
                <button type="submit" class="btn btn-primary flex-1">Update</button>
                <button type="button" onclick="document.getElementById('editCategoryModal').classList.add('hidden')" class="btn btn-outline flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function editCategory(category) {
    const form = document.getElementById('editCategoryForm');
    form.action = `/admin/account-content/feedback/category/${category.id}/update`;
    document.getElementById('editLabel').value = category.label;
    document.getElementById('editCategoryModal').classList.remove('hidden');
}
</script>
@endsection
