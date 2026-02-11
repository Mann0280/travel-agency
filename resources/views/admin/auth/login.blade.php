<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personnel Authentication - {{ $site_settings->get('site_name', 'ZUBEEE') }} ADMIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --forest: #1A3C34;
            --forest-dark: #0F2A24;
            --gold: #C5A059;
            --champagne: #F4EBD0;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--forest-dark);
            background-image: 
                radial-gradient(at 0% 0%, hsla(166, 39%, 17%, 1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(166, 39%, 12%, 1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(166, 39%, 17%, 1) 0, transparent 50%);
        }
        .font-poppins { font-family: 'Poppins', sans-serif; }
        .bg-forest { background-color: var(--forest); }
        .text-forest { color: var(--forest); }
        .text-gold { color: var(--gold); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full relative">
        <!-- Decorative Elements -->
        <div class="absolute -top-12 -left-12 w-32 h-32 bg-gold/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-12 -right-12 w-32 h-32 bg-forest/30 rounded-full blur-3xl"></div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden relative border border-white/20">
            <!-- Branding Header -->
            <div class="bg-forest p-12 text-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0 100 L100 0 L100 100 Z" fill="white"></path>
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white rounded-2xl rotate-12 flex items-center justify-center mx-auto mb-6 shadow-xl overflow-hidden">
                        @if($site_settings->get('site_logo'))
                            <img src="{{ asset($site_settings->get('site_logo')) }}" class="w-full h-full object-contain -rotate-12" alt="Logo">
                        @else
                            <i class="fas fa-compass text-forest text-3xl -rotate-12"></i>
                        @endif
                    </div>
                    <h1 class="text-3xl font-black text-white font-poppins tracking-tighter uppercase mb-2">{{ $site_settings->get('site_name', 'ZUBEEE') }} Admin</h1>
                    <p class="text-[10px] font-black text-gold uppercase tracking-[0.3em]">Institutional Access Portal</p>
                </div>
            </div>
            
            <div class="p-10">
                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8 rounded-r-xl">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-shield-virus text-red-500"></i>
                            <p class="text-xs font-bold text-red-700 uppercase tracking-tight">
                                {{ $errors->first() }}
                            </p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.login') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-[10px] font-black text-forest uppercase tracking-widest mb-2 ml-1">Command Identity (Email)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-id-badge text-forest/30 group-focus-within:text-gold transition-colors"></i>
                            </div>
                            <input type="email" id="email" name="email" required 
                                   class="block w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-forest/10 focus:border-forest text-sm font-medium text-forest transition-all" 
                                   placeholder="admin@zubeee.com" value="{{ old('email') }}">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-[10px] font-black text-forest uppercase tracking-widest mb-2 ml-1">Access Protocol (Password)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-key text-forest/30 group-focus-within:text-gold transition-colors"></i>
                            </div>
                            <input type="password" id="password" name="password" required 
                                   class="block w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-forest/10 focus:border-forest text-sm font-medium text-forest transition-all" 
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between py-2">
                        <label class="relative inline-flex items-center cursor-pointer group">
                            <input id="remember" name="remember" type="checkbox" class="sr-only peer">
                            <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2.5px] after:left-[2.5px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-forest"></div>
                            <span class="ml-3 text-[10px] font-black text-slate uppercase tracking-widest">Persist Session</span>
                        </label>
                        <a href="#" class="text-[10px] font-black text-gold uppercase tracking-widest hover:text-forest transition-colors">Lost Protocol?</a>
                    </div>

                    <button type="submit" 
                            class="w-full bg-forest hover:bg-forest-dark text-white font-black uppercase tracking-[0.2em] text-xs py-5 rounded-2xl shadow-xl shadow-forest/20 transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98]">
                        Authenticate & Engage
                    </button>
                </form>
            </div>
            
            <div class="bg-gray-50 px-10 py-6 text-center border-t border-gray-100 flex items-center justify-center gap-2">
                <i class="fas fa-user-secret text-slate/20"></i>
                <p class="text-[9px] font-black text-slate/40 uppercase tracking-widest">Encrypted Institutional Gateway</p>
            </div>
        </div>
        
        <p class="text-center mt-8 text-[10px] font-black text-white/20 uppercase tracking-[0.4em]">© {{ date('Y') }} {{ $site_settings->get('site_name', 'ZUBEEE') }} Travel Systems</p>
    </div>
</body>
</html>
