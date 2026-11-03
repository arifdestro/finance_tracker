<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name', 'Finance Tracker') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        function applyTheme() {
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        applyTheme();
    </script>

    <style>
        * { font-family: 'Inter', sans-serif; }

        /* Animated Gradient Background */
        .bg-mesh {
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(236, 72, 153, 0.15) 0px, transparent 50%);
        }
        
        .dark .bg-mesh {
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(236, 72, 153, 0.1) 0px, transparent 50%);
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delayed { animation: float 6s ease-in-out 3s infinite; }
        
        @keyframes fade-in-up {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.6s ease-out forwards; }
    </style>
</head>
<body class="antialiased bg-slate-50 dark:bg-slate-950 bg-mesh text-slate-800 dark:text-slate-200 transition-colors duration-300 min-h-screen flex flex-col items-center justify-center relative overflow-hidden">
    
    <!-- Decorative Orbs -->
    <div class="absolute top-0 right-1/4 w-[400px] h-[400px] bg-indigo-500/20 dark:bg-indigo-500/10 rounded-full blur-[100px] animate-float pointer-events-none z-0"></div>
    <div class="absolute bottom-0 left-1/4 w-[400px] h-[400px] bg-violet-500/20 dark:bg-violet-500/10 rounded-full blur-[100px] animate-float-delayed pointer-events-none z-0"></div>

    <div class="relative z-10 w-full max-w-lg px-6 animate-fade-in-up">
        
        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <a href="{{ url('/') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-500/25">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800 dark:text-white tracking-tight">Finance</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium tracking-widest uppercase">Tracker</p>
                </div>
            </a>
        </div>

        <!-- Error Card -->
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-2xl rounded-3xl border border-slate-200 dark:border-white/10 p-8 md:p-10 shadow-2xl shadow-indigo-500/5 dark:shadow-black/20 text-center relative overflow-hidden group">
            
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-purple-500/5 dark:from-white/[0.02] dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            
            <div class="relative z-10">
                <div class="mb-6 flex justify-center">
                    @yield('icon')
                </div>
                
                <h1 class="text-6xl md:text-7xl font-black bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-violet-500 dark:from-indigo-400 dark:to-violet-400 mb-2">
                    @yield('code')
                </h1>
                
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-4">
                    @yield('title')
                </h2>
                
                <p class="text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
                    @yield('message')
                </p>
                
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button onclick="window.history.back()" class="px-6 py-3 rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 hover:bg-slate-50 dark:hover:bg-white/10 text-slate-700 dark:text-slate-300 font-medium transition-all duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-400/20">
                        {{ __('Kembali') }}
                    </button>
                    <a href="{{ url('/') }}" class="px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-medium transition-all duration-200 shadow-lg shadow-indigo-500/25 focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                        {{ __('Ke Halaman Utama') }}
                    </a>
                </div>
            </div>
        </div>
        
        <div class="mt-8 text-center text-sm text-slate-500 dark:text-slate-500">
            &copy; {{ date('Y') }} Finance Tracker.
        </div>
    </div>
</body>
</html>
