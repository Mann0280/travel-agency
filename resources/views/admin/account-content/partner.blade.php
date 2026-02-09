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
            <h2 class="text-2xl font-bold text-[#17320b]">Manage Partner Section</h2>
            <p class="text-gray-600">Update the Partner With Us section content</p>
        </div>
        <a href="{{ route('admin.account-content.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Form -->
        <div class="card">
            <h3 class="text-lg font-bold text-[#17320b] mb-4">Edit Partner Content</h3>
            <form action="{{ route('admin.account-content.partner.update') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                        <textarea name="description" rows="2" required class="w-full form-input">{{ $partnerData['description'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Benefits *</label>
                        <textarea name="benefits" rows="4" required class="w-full form-input" placeholder="One per line">{{ implode("\n", $partnerData['benefits'] ?? []) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Requirements *</label>
                        <textarea name="requirements" rows="4" required class="w-full form-input" placeholder="One per line">{{ implode("\n", $partnerData['requirements'] ?? []) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Apply Button Text</label>
                        <input type="text" name="apply_button_text" value="{{ $partnerData['apply_button_text'] ?? 'Apply Now' }}" required class="form-input">
                    </div>
                    <button type="submit" class="btn btn-primary w-full shadow-sm">
                        <i class="fas fa-save mr-2"></i> Update Partner Content
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Mobile Preview -->
        <div class="card">
            <h3 class="text-lg font-bold text-text mb-4">Mobile Preview</h3>
            <p class="text-gray-600 mb-4">This is how the Partner tab will appear:</p>
            
            <div class="bg-background border border-secondary rounded-lg p-4 max-w-sm mx-auto shadow-sm">
                <!-- Tabs Preview -->
                <div class="flex overflow-x-auto mb-4 pb-2">
                    <div class="flex space-x-2">
                        <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full border border-secondary text-text">Profile</span>
                        <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full border border-secondary text-text">About</span>
                        <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full bg-secondary text-white">Partner</span>
                        <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full border border-secondary text-text">Help</span>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <div class="text-center py-4 border-b border-gray-50">
                        <i class="fas fa-handshake text-3xl text-secondary mb-3"></i>
                        <p class="text-[11px] text-gray-600 leading-relaxed font-medium">
                            {{ $partnerData['description'] ?? 'Join our elite network of travel partners.' }}
                        </p>
                    </div>

                    <div>
                        <h4 class="text-[11px] font-bold text-text mb-3 uppercase tracking-wider">Your Benefits</h4>
                        <div class="grid grid-cols-1 gap-2">
                            @foreach ($partnerData['benefits'] ?? [] as $benefit)
                                <div class="flex items-center text-[10px] text-gray-700 bg-gray-50 p-2 rounded">
                                    <div class="w-4 h-4 rounded-full bg-secondary/10 flex items-center justify-center mr-2">
                                        <i class="fas fa-check text-secondary text-[8px]"></i>
                                    </div>
                                    <span>{{ $benefit }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-2">
                        <button class="w-full bg-[#17320b] text-white py-3 rounded-lg text-xs font-bold uppercase tracking-widest shadow-md">
                            {{ $partnerData['apply_button_text'] ?? 'Apply Now' }}
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="text-xs text-gray-500 mt-6 text-center italic">
                <i class="fas fa-info-circle mr-1"></i> Preview of the Partner enrollment experience.
            </div>
        </div>
    </div>
</div>
@endsection
