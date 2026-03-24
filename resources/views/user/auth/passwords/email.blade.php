@extends('layouts.frontend')

@section('title', $site_settings->get('site_name', 'ZUBEEE') . ' - Forgot Password')

@push('styles')
<style>
    body { background-color: #17320b !important; }
    .login-container { min-height: 100vh; background-color: #17320b; color: #ffffff; }
    .premium-input { background-color: transparent !important; border: 1px solid #a8894d !important; color: #a8894d !important; border-radius: 8px; padding: 12px 16px; transition: all 0.3s ease; }
    .premium-input::placeholder { color: #a8894d80 !important; }
    .premium-input:focus { outline: none; box-shadow: 0 0 0 2px rgba(168, 137, 77, 0.2); border-color: #dbb363 !important; }
    .gold-button { background-color: #a8894d !important; color: #ffffff !important; border-radius: 8px; padding: 12px; font-weight: 600; transition: all 0.3s ease; }
    .gold-button:hover { background-color: #dbb363 !important; transform: translateY(-1px); }
    .gold-link { color: #a8894d !important; transition: color 0.3s ease; }
    .gold-link:hover { color: #dbb363 !important; }
    header, footer { display: none !important; }
</style>
@endpush

@section('content')
<div class="login-container flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8" data-aos="fade-up">
        <div class="text-center">
            <div class="flex justify-center items-center space-x-2 mb-4">
                <span class="text-3xl font-bold text-white tracking-wider">{{ $site_settings->get('site_name', 'ZUBEEE') }}</span>
            </div>
            <h2 class="mt-6 text-4xl font-extrabold text-white">Forgot Password?</h2>
            <p class="mt-4 text-sm text-gray-300">
                Enter your email address and we'll send you a link to reset your password.
            </p>
        </div>

        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-sm" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('password.email') }}" method="POST">
            @csrf
            <div>
                <input id="email" name="email" type="email" autocomplete="email" required 
                       class="premium-input w-full" 
                       placeholder="Email address" value="{{ request('email') ?? old('email') }}">
            </div>

            <div>
                <button type="submit" class="gold-button w-full shadow-lg">
                    Send Reset Link
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="gold-link text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
