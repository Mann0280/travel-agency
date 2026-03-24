@extends('layouts.frontend')

@section('title', $site_settings->get('site_name', 'ZUBEEE') . ' - Login')

@push('styles')
<style>
    /* Premium Login Page Styles */
    body {
        background-color: #17320b !important;
    }
    
    .login-container {
        min-height: 100vh;
        background-color: #17320b;
        color: #ffffff;
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
<div class="login-container flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8" data-aos="fade-up">
        <div class="text-center">
            <h1 class="text-4xl font-black text-white uppercase tracking-tighter mb-4">Welcome Back</h1>
            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-sm" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-sm" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <div class="flex justify-center items-center space-x-2 mb-4">
                <span class="text-3xl font-bold text-white tracking-wider">{{ $site_settings->get('site_name', 'ZUBEEE') }}</span>
            </div>
            <h2 class="mt-6 text-4xl font-extrabold text-white">
                Sign in to your account
            </h2>
            <p class="mt-4 text-sm text-gray-300">
                Or <a href="{{ route('register') }}" class="gold-link font-medium">create a new account</a>
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
        
        <form id="loginForm" class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="relative">
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="premium-input w-full" 
                           placeholder="Email address" value="{{ old('email') }}">
                </div>
                <div class="relative">
                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                           class="premium-input w-full" 
                           placeholder="Password">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember_me" type="checkbox" 
                           class="h-4 w-4 bg-transparent border-[#a8894d] text-[#a8894d] rounded focus:ring-0">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-300">
                        Remember me
                    </label>
                </div>

                <div class="text-xs">
                    <a href="{{ route('password.request') }}" id="forgot-password-link" class="font-medium text-gray-400 cursor-not-allowed pointer-events-none transition opacity-50">
                        Forgot Password?
                    </a>
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex items-center">
                    <input id="agree_terms" name="agree_terms" type="checkbox" required
                           class="h-4 w-4 bg-transparent border-[#a8894d] text-[#a8894d] rounded focus:ring-0">
                    <label for="agree_terms" class="ml-2 block text-sm text-gray-300">
                        By continuing, you agree to our <a href="{{ \App\Models\Setting::get('terms_conditions_url', '/info/terms') }}" target="_blank" class="text-secondary hover:text-gold transition underline">Terms & Conditions</a> and <a href="/info/privacy" target="_blank" class="text-secondary hover:text-gold transition underline">Privacy Policy</a>
                    </label>
                </div>
                <div id="terms-error" class="hidden text-red-500 text-xs mt-1">Please agree to the Terms & Conditions to proceed.</div>
            </div>

            <div>
                <button type="submit" class="gold-button w-full shadow-lg">
                    Sign in
                </button>
            </div>
            
            <div class="mt-8">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-[#17320b] text-gray-400">
                            Demo Credentials
                        </span>
                    </div>
                </div>
                <div class="mt-6 text-center text-sm text-gray-300">
                    <p>Email: demo@Zubeee.com</p>
                    <p>Password: password</p>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const forgotLink = document.getElementById('forgot-password-link');
    const loginForm = document.getElementById('loginForm');
    const termsCheckbox = document.getElementById('agree_terms');
    const termsError = document.getElementById('terms-error');
    
    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function updateForgotLink() {
        if (validateEmail(emailInput.value)) {
            forgotLink.classList.remove('text-gray-400', 'cursor-not-allowed', 'pointer-events-none', 'opacity-50');
            forgotLink.classList.add('gold-link'); // Matching the theme class
            
            // Append email to link
            const baseUrl = "{{ route('password.request') }}";
            forgotLink.href = `${baseUrl}?email=${encodeURIComponent(emailInput.value)}`;
        } else {
            forgotLink.classList.add('text-gray-400', 'cursor-not-allowed', 'pointer-events-none', 'opacity-50');
            forgotLink.classList.remove('gold-link');
            forgotLink.href = "{{ route('password.request') }}";
        }
    }

    emailInput.addEventListener('input', updateForgotLink);
    
    // Initial check
    setTimeout(updateForgotLink, 500);

    // Form submission validation
    loginForm.addEventListener('submit', function(e) {
        if (!termsCheckbox.checked) {
            e.preventDefault();
            termsError.classList.remove('hidden');
            termsCheckbox.focus();
        } else {
            termsError.classList.add('hidden');
        }
    });
});
</script>
@endpush
