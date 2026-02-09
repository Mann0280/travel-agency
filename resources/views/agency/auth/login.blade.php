<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agency Login | ZUBEEE Partner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: {
                            light: '#2d5a27',
                            DEFAULT: '#1a3a1a',
                            dark: '#0f240f',
                        },
                        gold: '#c5a059',
                        slate: '#4a5568',
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 10px 40px -10px rgba(0,0,0,0.1)',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#f8fafc] font-poppins min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Logo/Branding -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-forest text-gold text-3xl shadow-2xl mb-6">
                <i class="fas fa-briefcase"></i>
            </div>
            <h1 class="text-3xl font-black text-forest-dark uppercase tracking-tighter">ZUBEEE <span class="text-gold">Partner</span></h1>
            <p class="text-slate text-sm font-medium mt-2">Travel Agency Management Portal</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-[2.5rem] p-10 shadow-soft border border-gray-100">
            <h2 class="text-xl font-bold text-forest-dark mb-8">Partner Access</h2>

            @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl">
                <p class="text-red-700 text-xs font-bold">{{ session('error') }}</p>
            </div>
            @endif

            <form action="{{ route('agency.login.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-forest uppercase tracking-widest mb-2 ml-1">Official Email</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate/40 text-sm">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="email" required 
                               class="w-full pl-12 pr-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:border-forest focus:ring-4 focus:ring-forest/5 outline-none transition-all font-medium text-sm text-forest-dark"
                               placeholder="agency@example.com">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-forest uppercase tracking-widest mb-2 ml-1">Security Key</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate/40 text-sm">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" required 
                               class="w-full pl-12 pr-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:border-forest focus:ring-4 focus:ring-forest/5 outline-none transition-all font-medium text-sm text-forest-dark"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between px-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-forest focus:ring-forest">
                        <span class="text-[11px] font-bold text-slate uppercase tracking-wide group-hover:text-forest transition-colors">Keep me signed in</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-4 bg-gradient-to-r from-forest to-forest-dark text-white font-black uppercase tracking-widest text-xs rounded-2xl shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 mt-4">
                    Authenticate Account
                </button>
            </form>
        </div>

        <!-- Support Info -->
        <p class="text-center mt-8 text-[10px] font-bold text-slate uppercase tracking-widest">
            Need support? <a href="#" class="text-gold hover:text-forest transition-colors">Contact Administrator</a>
        </p>
    </div>
</body>
</html>
