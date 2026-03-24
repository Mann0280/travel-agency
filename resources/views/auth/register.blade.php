<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZUBEEE - Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
        :root {
            --primary: #17320B;
            --secondary: #A8894D;
            --accent: #f3f4f6;
            --gold: #d7c40f;
        }
        body {
            background-color: var(--primary);
            color: var(--accent);
        }
        .bg-primary { background-color: var(--primary); }
        .bg-secondary { background-color: var(--secondary); }
        .text-accent { color: var(--accent); }
        .text-secondary { color: var(--secondary); }
        .text-gold { color: var(--gold); }
        .border-secondary { border-color: var(--secondary); }
        .focus\:ring-secondary:focus { --tw-ring-color: var(--secondary); }
        .focus\:border-secondary:focus { border-color: var(--secondary); }
    </style>
</head>
<body class="bg-primary">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8" data-aos="fade-up">
            <div>
                <div class="flex justify-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <span class="text-4xl font-bold text-gray-300">ZUBEEE</span>
                    </a>

                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-100">
                    Create your account
                </h2>
                <p class="mt-2 text-center text-sm text-gray-300">
                    Or
                    <a href="{{ route('login') }}" class="font-medium text-secondary hover:text-gold transition">
                        sign in to your existing account
                    </a>
                </p>
            </div>
            
            @if($errors->any())
            <div class="bg-red-900/50 border border-red-700 text-red-100 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <form id="registerForm" class="mt-8 space-y-6" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="rounded-md shadow-sm space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Full Name</label>
                        <input id="name" name="name" type="text" autocomplete="name" required 
                            class="appearance-none relative block w-full px-4 py-3 border border-secondary placeholder-gray-500 text-white rounded-xl focus:outline-none focus:ring-secondary focus:border-secondary focus:z-10 sm:text-sm bg-[#17320B]" 
                            placeholder="Your full name" 
                            value="{{ old('name') }}">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="appearance-none relative block w-full px-4 py-3 border border-secondary placeholder-gray-500 text-white rounded-xl focus:outline-none focus:ring-secondary focus:border-secondary focus:z-10 sm:text-sm bg-[#17320B]" 
                            placeholder="Email address" 
                            value="{{ old('email') }}">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required 
                            class="appearance-none relative block w-full px-4 py-3 border border-secondary placeholder-gray-500 text-white rounded-xl focus:outline-none focus:ring-secondary focus:border-secondary focus:z-10 sm:text-sm bg-[#17320B]" 
                            placeholder="Password (min. 6 characters)">
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                            class="appearance-none relative block w-full px-4 py-3 border border-secondary placeholder-gray-500 text-white rounded-xl focus:outline-none focus:ring-secondary focus:border-secondary focus:z-10 sm:text-sm bg-[#17320B]" 
                            placeholder="Confirm your password">
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="agree_terms" name="agree_terms" type="checkbox" required 
                        class="h-4 w-4 text-secondary focus:ring-secondary border-gray-600 bg-transparent rounded">
                    <label for="agree_terms" class="ml-2 block text-sm text-gray-300">
                        I agree to the <a href="{{ \App\Models\Setting::get('terms_conditions_url', '/info/terms') }}" target="_blank" class="text-secondary hover:text-gold transition">Terms and Conditions</a> and <a href="/info/privacy" target="_blank" class="text-secondary hover:text-gold transition">Privacy Policy</a>
                    </label>
                </div>
                <div id="terms-error" class="hidden text-red-500 text-xs mt-1">Please agree to the Terms & Conditions to proceed.</div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-secondary hover:bg-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary transition-all transform hover:-translate-y-1 shadow-lg">
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        AOS.init({
            duration: 800,
            once: true
        });

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
</body>
</html>
