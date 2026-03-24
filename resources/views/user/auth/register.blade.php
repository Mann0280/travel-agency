@extends('layouts.frontend')

@section('title', $site_settings->get('site_name', 'ZUBEEE') . ' - Register')

@push('styles')
<style>
    /* Premium Register Page Styles */
    body {
        background-color: #17320b !important;
    }
    
    .register-container {
        min-height: 100vh;
        background-color: #17320b;
        color: #ffffff;
    }
    
    .premium-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #ffffff;
        margin-bottom: 0.5rem;
    }
    
    .premium-input {
        background-color: transparent !important;
        border: 1px solid #a8894d !important;
        color: #a8894d !important;
        border-radius: 8px;
        padding: 12px 16px;
        transition: all 0.3s ease;
    }
    
    .premium-input::placeholder {
        color: #a8894d80 !important;
    }
    
    .premium-input:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(168, 137, 77, 0.2);
        border-color: #dbb363 !important;
    }
    
    .gold-button {
        background-color: #a8894d !important;
        color: #ffffff !important;
        border-radius: 8px;
        padding: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .gold-button:hover {
        background-color: #dbb363 !important;
        transform: translateY(-1px);
    }
    
    .gold-link {
        color: #a8894d !important;
        transition: color 0.3s ease;
    }
    
    .gold-link:hover {
        color: #dbb363 !important;
    }

    /* Hide standard header/footer for auth pages */
    header, footer {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="register-container flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8" data-aos="fade-up">
        <div class="text-center">
            <div class="flex justify-center items-center space-x-2 mb-4">
                <span class="text-3xl font-bold text-white tracking-wider">{{ $site_settings->get('site_name', 'ZUBEEE') }}</span>
            </div>
            <h2 class="mt-6 text-4xl font-extrabold text-white">
                Create your account
            </h2>
            <p class="mt-4 text-sm text-gray-300">
                Or <a href="{{ route('login') }}" class="gold-link font-medium">sign in to your existing account</a>
            </p>
        </div>
        
        @if($errors->any())
            <div class="bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form id="registerForm" class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="name" class="premium-label">Full Name</label>
                    <input id="name" name="name" type="text" autocomplete="name" required 
                           class="premium-input w-full" 
                           placeholder="Your full name" value="{{ old('name') }}">
                </div>
                
                <div>
                    <label for="email" class="premium-label">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="premium-input w-full" 
                           placeholder="Email address" value="{{ old('email') }}">
                </div>
                
                <div>
                    <label for="password" class="premium-label">Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required 
                           class="premium-input w-full" 
                           placeholder="Password (min. 6 characters)">
                </div>
                
                <div>
                    <label for="password_confirmation" class="premium-label">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                           class="premium-input w-full" 
                           placeholder="Confirm your password">
                </div>
            </div>

            <div class="flex items-center">
                <input id="agree_terms" name="agree_terms" type="checkbox" required
                       class="h-4 w-4 bg-transparent border-[#a8894d] text-[#a8894d] rounded focus:ring-0">
                <label for="agree_terms" class="ml-2 block text-sm text-gray-300">
                        I agree to the <a href="{{ \App\Models\Setting::get('terms_conditions_url', '/info/terms') }}" target="_blank" class="text-secondary hover:text-gold transition">Terms and Conditions</a> and <a href="/info/privacy" target="_blank" class="text-secondary hover:text-gold transition">Privacy Policy</a>
                </label>
            </div>
            <div id="terms-error" class="hidden text-red-500 text-xs mt-1">Please agree to the Terms & Conditions to proceed.</div>

            <div>
                <button type="submit" class="gold-button w-full shadow-lg">
                    Create Account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const checkbox = document.getElementById('agree_terms');
        const errorDiv = document.getElementById('terms-error');
        
        if (!checkbox.checked) {
            e.preventDefault();
            errorDiv.classList.remove('hidden');
            checkbox.focus();
        } else {
            errorDiv.classList.add('hidden');
        }
    });
</script>
@endpush
