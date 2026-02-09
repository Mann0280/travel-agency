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
            <h2 class="text-2xl font-bold text-[#17320b]">Manage About Us</h2>
            <p class="text-gray-600">Update the About Us section content</p>
        </div>
        <a href="{{ route('admin.account-content.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Form -->
        <div class="card">
            <h3 class="text-lg font-bold text-[#17320b] mb-4">Edit About Us Content</h3>
            <form action="{{ route('admin.account-content.about.update') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                        <textarea name="description" rows="2" required class="w-full form-input">{{ $aboutData['description'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mission Statement *</label>
                        <textarea name="mission" rows="2" required class="w-full form-input">{{ $aboutData['mission'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Core Values *</label>
                        <textarea name="values" rows="4" required class="w-full form-input" placeholder="One per line">{{ implode("\n", $aboutData['values'] ?? []) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Why Choose Us *</label>
                        <textarea name="why_choose" rows="4" required class="w-full form-input" placeholder="One per line">{{ implode("\n", $aboutData['why_choose'] ?? []) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Update About Us</button>
                </div>
            </form>
        </div>
        
        <!-- Mobile Preview -->
        <div class="card">
            <h3 class="text-lg font-bold text-text mb-4">Mobile Preview</h3>
            <p class="text-gray-600 mb-4">This is how the About tab will appear:</p>
            
            <div class="bg-background border border-secondary rounded-lg p-4 max-w-sm mx-auto shadow-sm">
                <!-- Tabs Preview -->
                <div class="flex overflow-x-auto mb-4 pb-2">
                    <div class="flex space-x-2">
                        <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full border border-secondary text-text">Profile</span>
                        <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full border border-secondary text-text">Help</span>
                        <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full bg-secondary text-white">About</span>
                        <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full border border-secondary text-text">Partner</span>
                    </div>
                </div>
                
                <div class="space-y-5">
                    <div class="border-b border-gray-100 pb-3">
                        <h4 class="text-sm font-bold text-text mb-2 uppercase tracking-wide">Our Mission</h4>
                        <p class="text-xs text-gray-600 leading-relaxed italic">"{{ $aboutData['mission'] ?? 'No mission set.' }}"</p>
                    </div>

                    <div>
                        <h4 class="text-sm font-bold text-text mb-3 uppercase tracking-wide">Core Values</h4>
                        <div class="grid grid-cols-1 gap-2">
                            @foreach ($aboutData['values'] ?? [] as $value)
                                <div class="flex items-center text-[10px] text-gray-700 bg-gray-50 p-2 rounded">
                                    <i class="fas fa-check-circle text-secondary mr-2"></i>
                                    <span>{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-[#17320b] p-4 rounded-lg text-white shadow-sm">
                        <h4 class="text-xs font-bold text-secondary mb-2 uppercase">Why ZUBEEE?</h4>
                        <ul class="space-y-2">
                            @foreach ($aboutData['why_choose'] ?? [] as $reason)
                                <li class="text-[10px] flex items-start">
                                    <i class="fas fa-star text-secondary mr-2 mt-0.5"></i>
                                    <span>{{ $reason }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="text-xs text-gray-500 mt-6 text-center italic">
                <i class="fas fa-info-circle mr-1"></i> Live preview of the About section content.
            </div>
        </div>
    </div>
</div>
@endsection
