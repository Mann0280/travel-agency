@extends('layouts.frontend')

@section('title', $site_settings->get('site_name', 'ZUBEEE') . ' - Reset Password')

@push('styles')
<style>
    body { background-color: #17320b !important; }
    .login-container { min-height: 100vh; background-color: #17320b; color: #ffffff; }
    .premium-input { background-color: transparent !important; border: 1px solid #a8894d !important; color: #a8894d !important; border-radius: 8px; padding: 12px 16px; transition: all 0.3s ease; }
    .premium-input:disabled { opacity: 0.6; cursor: not-allowed; background-color: rgba(255,255,255,0.05) !important; }
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
            <h2 class="mt-6 text-4xl font-extrabold text-white">Reset Password</h2>
            <p class="mt-4 text-sm text-gray-300">
                Please enter your new password below.
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

        <form class="mt-8 space-y-6" action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Email Address</label>
                    <input id="email" name="email" type="email" required readonly 
                           class="premium-input w-full" 
                           value="{{ $email }}">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">New Password</label>
                    <input id="password" name="password" type="password" required 
                           class="premium-input w-full" 
                           placeholder="At least 8 chars, 1 uppercase, 1 number">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Confirm New Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                           class="premium-input w-full" 
                           placeholder="Repeat your new password">
                </div>
            </div>

            <div>
                <button type="submit" class="gold-button w-full shadow-lg">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
