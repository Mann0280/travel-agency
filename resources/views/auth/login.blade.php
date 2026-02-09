<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZUBEEE - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
        :root {
            --primary: #17320B;
            --secondary: #A8894D;
            --accent: #f3f4f6; /* gray-100 for better visibility on dark */
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
                        <span class="text-sm text-gray-400 mt-2">Tours & Travels</span>
                    </a>
                </div>
                <h2 class="mt-6 text-center text-gray-100 text-3xl font-extrabold">
                    Sign in to your account
                </h2>
                <p class="mt-2 text-center text-sm text-gray-300">
                    Or
                    <a href="{{ route('register') }}" class="font-medium text-secondary hover:text-gold transition">
                        create a new account
                    </a>
                </p>
            </div>
            
            @if($errors->any())
            <div class="bg-red-900/50 border border-red-700 text-red-100 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ $errors->first() }}</span>
            </div>
            @endif

            @if(session('success'))
            <div class="bg-green-900/50 border border-green-700 text-green-100 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif
            
            <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">Email address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="appearance-none rounded-none relative block w-full px-4 py-3 border border-secondary placeholder-gray-500 text-white rounded-t-xl focus:outline-none focus:ring-secondary focus:border-secondary focus:z-10 sm:text-sm bg-[#17320B]" 
                            placeholder="Email address" 
                            value="{{ old('email') }}">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="appearance-none rounded-none relative block w-full px-4 py-3 border border-secondary placeholder-gray-500 text-white rounded-b-xl focus:outline-none focus:ring-secondary focus:border-secondary focus:z-10 sm:text-sm bg-[#17320B]" 
                            placeholder="Password">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" 
                            class="h-4 w-4 text-secondary focus:ring-secondary border-gray-600 bg-transparent rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-300">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-secondary hover:text-gold transition">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-secondary hover:bg-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary transition-all transform hover:-translate-y-1 shadow-lg">
                        Sign in
                    </button>
                </div>
                
                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-[#17320B] text-gray-400 font-medium">
                                Demo Credentials
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 p-4 bg-black/20 rounded-xl border border-white/5 text-center text-sm text-gray-400">
                        <p class="flex justify-between px-4"><span>Email:</span> <span class="text-gray-200">demo@zubeee.com</span></p>
                        <p class="flex justify-between px-4 mt-1"><span>Password:</span> <span class="text-gray-200">password</span></p>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html>
