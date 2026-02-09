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
    .status-active { background-color: #dcfce7; color: #166534; }
    .status-inactive { background-color: #fee2e2; color: #991b1b; }
    .badge { padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
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
            <h2 class="text-2xl font-bold text-[#17320b]">Manage FAQ</h2>
            <p class="text-gray-600">Help Center Frequently Asked Questions</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.account-content.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
            <button onclick="document.getElementById('addFaqModal').classList.remove('hidden')" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Add New FAQ
            </button>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Question & Answer</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($faqs as $faq)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-4">
                                <div class="text-sm font-bold text-[#17320b]">{{ $faq->question }}</div>
                                <div class="text-xs text-gray-600 mt-1">{{ Str::limit($faq->answer, 120) }}</div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500 font-medium">#{{ $faq->sort_order }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-3">
                                    <form action="{{ route('admin.account-content.faq.update', $faq->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="toggle_status" value="1">
                                        <button type="submit" class="badge {{ $faq->status === 'active' ? 'status-active' : 'status-inactive' }} hover:opacity-80 transition cursor-pointer">
                                            {{ ucfirst($faq->status) }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-3">
                                    <button onclick="editFaq(@json($faq))" class="text-secondary hover:text-[#9d7c4f] transition"><i class="fas fa-edit"></i></button>
                                    <form action="{{ route('admin.account-content.faq.destroy', $faq->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this FAQ?')">
                                        @csrf
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-6 text-gray-500">No FAQ found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Preview -->
    <div class="card">
        <h3 class="text-lg font-bold text-text mb-4">Mobile Preview</h3>
        <p class="text-gray-600 mb-4">This is how the Help Center will appear:</p>
        
        <div class="bg-background border border-secondary rounded-lg p-4 max-w-sm mx-auto shadow-sm">
            <!-- Tabs Preview -->
            <div class="flex overflow-x-auto mb-4 pb-2">
                <div class="flex space-x-2">
                    <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full border border-secondary text-text">Profile</span>
                    <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full bg-secondary text-white">Help</span>
                    <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full border border-secondary text-text">About</span>
                    <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full border border-secondary text-text">Partner</span>
                </div>
            </div>
            
            <div class="space-y-3">
                @php $previewFaqs = $faqs->where('status', 'active')->take(6); @endphp
                @forelse ($previewFaqs as $faq)
                    <div class="border border-gray-100 rounded-lg overflow-hidden transition-all">
                        <div class="flex justify-between items-center p-3 cursor-pointer bg-gray-50/50 toggle-faq">
                            <h5 class="text-[11px] font-bold text-text leading-tight pr-2">{{ $faq->question }}</h5>
                            <i class="fas fa-plus text-[9px] text-secondary"></i>
                        </div>
                        <div class="hidden p-3 bg-white border-t border-gray-50">
                            <p class="text-[10px] text-gray-600 leading-relaxed">{{ $faq->answer }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400 italic text-xs">No active FAQs to display.</div>
                @endforelse
            </div>
        </div>
        
        <div class="text-xs text-gray-500 mt-6 text-center italic">
            <i class="fas fa-info-circle mr-1"></i> Interactive preview. Content updates instantly.
        </div>
    </div>
</div>

<!-- Modals -->
<div id="addFaqModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 p-6 border-t-4 border-secondary">
        <h3 class="text-lg font-bold text-[#17320b] mb-4">Add New FAQ</h3>
        <form action="{{ route('admin.account-content.faq.store') }}" method="POST" class="space-y-4">
            @csrf
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Question</label><input type="text" name="question" required class="form-input"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Answer</label><textarea name="answer" rows="4" required class="form-input"></textarea></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label><input type="number" name="sort_order" value="{{ $faqs->count() + 1 }}" class="form-input"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Status</label><select name="status" class="form-input"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
            </div>
            <div class="flex space-x-3 pt-2">
                <button type="submit" class="btn btn-primary flex-1">Save FAQ</button>
                <button type="button" onclick="document.getElementById('addFaqModal').classList.add('hidden')" class="btn btn-outline flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div id="editFaqModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 p-6 border-t-4 border-secondary">
        <h3 class="text-lg font-bold text-[#17320b] mb-4">Edit FAQ</h3>
        <form id="editFaqForm" method="POST" class="space-y-4">
            @csrf
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Question</label><input type="text" name="question" id="editQuestion" required class="form-input"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Answer</label><textarea name="answer" id="editAnswer" rows="4" required class="form-input"></textarea></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label><input type="number" name="sort_order" id="editOrder" class="form-input"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Status</label><select name="status" id="editStatus" class="form-input"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
            </div>
            <div class="flex space-x-3 pt-2">
                <button type="submit" class="btn btn-primary flex-1">Update FAQ</button>
                <button type="button" onclick="document.getElementById('editFaqModal').classList.add('hidden')" class="btn btn-outline flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function editFaq(faq) {
    const form = document.getElementById('editFaqForm');
    form.action = `/admin/account-content/faq/${faq.id}/update`;
    document.getElementById('editQuestion').value = faq.question;
    document.getElementById('editAnswer').value = faq.answer;
    document.getElementById('editOrder').value = faq.sort_order;
    document.getElementById('editStatus').value = faq.status;
    document.getElementById('editFaqModal').classList.remove('hidden');
}

document.querySelectorAll('.toggle-faq').forEach(header => {
    header.addEventListener('click', function() {
        const content = this.nextElementSibling;
        const icon = this.querySelector('.fa-plus');
        const isHidden = content.classList.toggle('hidden');
        icon.classList.toggle('rotate-45', !isHidden);
    });
});
</script>
@endsection
