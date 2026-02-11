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
            <div class="flex justify-center items-center space-x-2 mb-4">
                <span class="text-3xl font-bold text-white tracking-wider">{{ $site_settings->get('site_name', 'ZUBEEE') }}</span>
                <span class="text-sm text-gray-300 font-medium">Tours & Travels</span>
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
        
        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
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

                <div class="text-sm">
                    <a href="#" class="gold-link font-medium">
                        Forgot your password?
                    </a>
                </div>
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
