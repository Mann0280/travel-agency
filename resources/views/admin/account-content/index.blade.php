@extends('admin.layouts.app')

@section('content')
<style>
    :root {
        --primary-color: #17320b;
        --secondary-color: #a8894d;
        --text-color: #374151;
        --bg-color: #f9fafb;
    }
    .card {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        padding: 1.5rem;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
        border: 1px solid transparent;
        font-size: 0.875rem;
    }
    .btn-primary { background-color: var(--secondary-color); color: white; }
    .btn-primary:hover { background-color: #9d7c4f; transform: translateY(-1px); }
    .btn-secondary { background-color: var(--primary-color); color: white; }
    .btn-secondary:hover { background-color: #1a3a0f; transform: translateY(-1px); }
    .text-secondary { color: var(--secondary-color); }
    
    /* Mapping the requested classes to CSS variables */
    .bg-background { background-color: white; }
    .border-secondary { border-color: var(--secondary-color); }
    .bg-secondary { background-color: var(--secondary-color); }
    .text-text { color: var(--primary-color); }
    .text-white { color: white; }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-[#17320b]">Manage My Account Page</h2>
            <p class="text-gray-600">Customize the content of the My Account page</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                    <i class="fas fa-question-circle text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-[#17320b]">{{ $activeFaqCount }}/{{ $faqCount }}</p>
                    <p class="text-gray-600">FAQ Items</p>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center mr-4">
                    <i class="fas fa-info-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-[#17320b]">{{ $valueCount }}</p>
                    <p class="text-gray-600">Core Values</p>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center mr-4">
                    <i class="fas fa-handshake text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-[#17320b]">{{ $benefitCount }}</p>
                    <p class="text-gray-600">Partner Benefits</p>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center mr-4">
                    <i class="fas fa-comment-alt text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-[#17320b]">{{ $categoryCount }}</p>
                    <p class="text-gray-600">Feedback Categories</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Management Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- FAQ Management -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-[#17320b]">FAQ Management</h3>
                <a href="{{ route('admin.account-content.faq') }}" class="text-secondary text-sm font-medium hover:text-[#9d7c4f]">Manage All</a>
            </div>
            
            <div class="space-y-3">
                @php
                    $displayFaqs = \App\Models\Faq::where('status', 'active')->orderBy('sort_order')->take(3)->get();
                @endphp
                @foreach ($displayFaqs as $faq)
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="text-xs font-medium text-gray-900">{{ $faq->question }}</div>
                    </div>
                @endforeach
            </div>
            
            <a href="{{ route('admin.account-content.faq') }}" class="block mt-4 btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Add New FAQ
            </a>
        </div>

        <!-- About Us Content -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-[#17320b]">About Us Content</h3>
                <a href="{{ route('admin.account-content.about') }}" class="text-secondary text-sm font-medium hover:text-[#9d7c4f]">Edit Content</a>
            </div>
            
            <div class="space-y-3">
                <p class="text-xs text-gray-600 bg-gray-50 p-2 rounded">{{ Str::limit($aboutData['mission'] ?? 'No mission statement set.', 150) }}</p>
            </div>
            
            <a href="{{ route('admin.account-content.about') }}" class="block mt-4 btn btn-secondary">
                <i class="fas fa-edit mr-2"></i> Edit About Us
            </a>
        </div>

        <!-- Partner Section -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-[#17320b]">Partner Section</h3>
                <a href="{{ route('admin.account-content.partner') }}" class="text-secondary text-sm font-medium hover:text-[#9d7c4f]">Manage</a>
            </div>
            
            <div class="space-y-3">
                <p class="text-xs text-gray-600 bg-gray-50 p-2 rounded">{{ Str::limit($partnerData['description'] ?? 'No partner information set.', 150) }}</p>
            </div>
            
            <a href="{{ route('admin.account-content.partner') }}" class="block mt-4 btn btn-secondary">
                <i class="fas fa-handshake mr-2"></i> Edit Partner Info
            </a>
        </div>

        <!-- Feedback Form -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-[#17320b]">Feedback Form</h3>
                <a href="{{ route('admin.account-content.feedback') }}" class="text-secondary text-sm font-medium hover:text-[#9d7c4f]">Configure</a>
            </div>
            
            <div class="space-y-2">
                @php $categories = \App\Models\FeedbackCategory::all(); @endphp
                @foreach ($categories->take(5) as $category)
                    <span class="text-xs bg-secondary/10 text-secondary px-2 py-1 rounded">{{ $category->label }}</span>
                @endforeach
            </div>
            
            <a href="{{ route('admin.account-content.feedback') }}" class="block mt-4 btn btn-secondary">
                <i class="fas fa-comment-alt mr-2"></i> Configure Feedback
            </a>
        </div>
    </div>

    <!-- Preview Section (MATCHING USER SNIPPET) -->
    <div class="card">
        <h3 class="text-lg font-bold text-text mb-4">Mobile Preview</h3>
        <p class="text-gray-600 mb-4">This is how your content will appear on the mobile app:</p>
        
        <div class="bg-background border border-secondary rounded-lg p-4 max-w-md mx-auto">
            <!-- Tabs Preview -->
            <div class="flex overflow-x-auto mb-4 pb-2">
                <div class="flex space-x-2">
                    <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full bg-secondary text-white">Profile</span>
                    <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full border border-secondary text-text">Bookings</span>
                    <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full border border-secondary text-text">Partner</span>
                    <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full border border-secondary text-text">Help</span>
                    <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full border border-secondary text-text">Feedback</span>
                    <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full border border-secondary text-text">About</span>
                </div>
            </div>
            
            <!-- Content Preview -->
            <div class="space-y-4">
                <!-- FAQ Preview -->
                <div class="border border-secondary rounded-lg p-3">
                    <h4 class="text-sm font-bold text-text mb-2">Help Center Preview</h4>
                    <div class="space-y-2">
                        @foreach ($displayFaqs->take(2) as $faq)
                            <div class="bg-gray-50 p-2 rounded">
                                <div class="text-xs font-medium text-text">{{ $faq->question }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- About Preview -->
                <div class="border border-secondary rounded-lg p-3">
                    <h4 class="text-sm font-bold text-text mb-2">About Us Preview</h4>
                    <p class="text-xs text-gray-600">{{ Str::limit($aboutData['mission'] ?? '', 80) }}</p>
                </div>
                
                <!-- Partner Preview -->
                <div class="border border-secondary rounded-lg p-3">
                    <h4 class="text-sm font-bold text-text mb-2">Partner Preview</h4>
                    <p class="text-xs text-gray-600">{{ Str::limit($partnerData['description'] ?? '', 80) }}</p>
                </div>
            </div>
            
            <div class="text-xs text-gray-500 mt-4 text-center">
                <i class="fas fa-info-circle mr-1"></i>
                This is a simplified preview. Actual mobile view may differ.
            </div>
        </div>
    </div>

    <!-- Instructions (MATCHING USER SNIPPET) -->
    <div class="card bg-blue-50 border-blue-200">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-xl mt-1"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-blue-800">My Account Page Guide</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li>FAQ items appear in the Help Center tab</li>
                        <li>About Us content appears in the About tab</li>
                        <li>Partner information appears in the Partner tab</li>
                        <li>Feedback categories are used in the feedback form</li>
                        <li>Changes are reflected immediately on the mobile app</li>
                        <li>Use the mobile preview to see how content will appear</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
