<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Finance Tracker') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // Sync theme with dashboard settings
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
        body { font-family: 'Inter', sans-serif; }
        
        /* Animated Gradient Background */
        .bg-mesh {
            background-image:
                radial-gradient(at 0% 0%, rgba(99,102,241,0.1) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(139,92,246,0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(236,72,153,0.1) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(56,189,248,0.1) 0px, transparent 50%);
        }
        
        .dark .bg-mesh {
            background-image:
                radial-gradient(at 0% 0%, rgba(99,102,241,0.15) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(139,92,246,0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(236,72,153,0.15) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(56,189,248,0.15) 0px, transparent 50%);
        }
        
        /* Floating animation */
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        @keyframes float-reverse {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(-2deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delayed { animation: float-reverse 7s ease-in-out 3s infinite; }
        .animate-float-fast { animation: float 5s ease-in-out 1s infinite; }

        /* Blob Animation */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</head>
<body class="antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-300 min-h-screen flex flex-col relative overflow-x-hidden">
    
    <!-- Background mesh & blobs -->
    <div class="fixed inset-0 bg-mesh z-0 pointer-events-none transition-colors duration-300"></div>
    
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden flex items-center justify-center">
        <div class="absolute top-0 -left-4 w-72 h-72 bg-indigo-400/30 dark:bg-indigo-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-purple-400/30 dark:bg-purple-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-400/30 dark:bg-pink-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border-b border-slate-200 dark:border-white/10 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-500/25">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-slate-800 dark:text-white">Finance<span class="text-indigo-600 dark:text-indigo-400">Tracker</span></span>
            </div>
            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-white transition-colors">{{ __('Dashboard') }}</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-block text-sm font-semibold text-slate-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-white transition-colors">{{ __('Log in') }}</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition-all hover:scale-105">{{ __('Mulai Sekarang') }}</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="relative z-10 flex-1 flex flex-col items-center pt-24 pb-32">
        <div class="max-w-7xl mx-auto px-6 w-full grid lg:grid-cols-2 gap-16 items-center">
            
            <!-- Left Content -->
            <div class="text-center lg:text-left z-20">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-100 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 text-indigo-700 dark:text-indigo-300 text-sm font-medium mb-8 mx-auto lg:mx-0">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-500 dark:bg-indigo-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600 dark:bg-indigo-500"></span>
                    </span>
                    {{ __('Versi 1.0 Telah Rilis') }}
                </div>
                
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-8 leading-[1.15]">
                    {{ __('Kendalikan') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-500 dark:from-indigo-400 dark:to-violet-400">{{ __('Keuangan') }}</span> {{ __('Anda,') }}<br/> {{ __('Wujudkan') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-400 dark:from-emerald-400 dark:to-teal-300">{{ __('Impian') }}</span> {{ __('Anda.') }}
                </h1>
                
                <p class="text-lg md:text-xl text-slate-600 dark:text-slate-400 mb-10 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    {{ __('Platform cerdas untuk mencatat pengeluaran, memantau anggaran, dan menganalisis tren keuangan Anda dalam satu tempat yang elegan, aman, dan intuitif.') }}
                </p>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-8 py-4 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold text-lg shadow-xl shadow-indigo-500/25 transition-all hover:scale-105 hover:-translate-y-1 w-full sm:w-auto text-center flex items-center justify-center gap-2">
                            {{ __('Mulai Gratis Sekarang') }}
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    @endif
                    <a href="#features" class="px-8 py-4 rounded-xl bg-white/50 dark:bg-slate-900/50 backdrop-blur-md border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white hover:bg-white/80 dark:hover:bg-slate-800/80 font-semibold text-lg transition-all w-full sm:w-auto text-center">
                        {{ __('Pelajari Fitur') }}
                    </a>
                </div>
                
                <!-- Trust indicators -->
                <div class="mt-12 pt-8 border-t border-slate-200 dark:border-white/10 flex items-center justify-center lg:justify-start gap-8 opacity-70 grayscale">
                    <div class="flex items-center gap-2 font-bold text-slate-800 dark:text-white"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15l-5-5 1.41-1.41L11 14.17l7.59-7.59L20 8l-9 9z"/></svg> Aman & Enkripsi</div>
                    <div class="flex items-center gap-2 font-bold text-slate-800 dark:text-white"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 9h-2V7h-2v5H6v2h2v5h2v-5h2v-2z"/></svg> Open Source</div>
                </div>
            </div>

            <!-- Right Graphic / Abstract UI -->
            <div class="relative hidden lg:block h-[500px] z-10 perspective-1000">
                <!-- Main Card -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-96 bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl rounded-3xl border border-slate-200/50 dark:border-white/10 shadow-2xl shadow-indigo-500/10 dark:shadow-black/40 p-6 animate-float">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 font-medium mb-1">{{ __('Total Saldo') }}</div>
                            <div class="text-2xl font-bold text-slate-800 dark:text-white">Rp 24.500.000</div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-3 rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-100 dark:border-white/5">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-bold text-slate-800 dark:text-white">Gaji Bulanan</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">Hari ini, 09:00</div>
                            </div>
                            <div class="text-sm font-bold text-emerald-600 dark:text-emerald-400">+ Rp 15M</div>
                        </div>
                        
                        <div class="flex items-center gap-4 p-3 rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-100 dark:border-white/5">
                            <div class="w-10 h-10 rounded-full bg-rose-100 dark:bg-rose-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-bold text-slate-800 dark:text-white">Belanja Bulanan</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">Kemarin, 19:20</div>
                            </div>
                            <div class="text-sm font-bold text-slate-800 dark:text-white">- Rp 2.5M</div>
                        </div>
                    </div>
                </div>
                
                <!-- Floating Element 1 -->
                <div class="absolute top-10 right-0 w-48 bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl border border-slate-200 dark:border-white/10 p-4 shadow-xl animate-float-delayed z-20">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div class="text-xs font-bold text-slate-800 dark:text-white">Anggaran Aman</div>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-1.5 mt-2">
                        <div class="bg-amber-500 h-1.5 rounded-full" style="width: 45%"></div>
                    </div>
                </div>

                <!-- Floating Element 2 -->
                <div class="absolute bottom-12 -left-8 w-56 bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl border border-slate-200 dark:border-white/10 p-4 shadow-xl animate-float-fast z-20">
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-2">Statistik Pengeluaran</div>
                    <div class="flex items-end gap-2 h-12">
                        <div class="w-full bg-indigo-500/20 rounded-t-sm h-[30%]"></div>
                        <div class="w-full bg-indigo-500/40 rounded-t-sm h-[50%]"></div>
                        <div class="w-full bg-indigo-500/60 rounded-t-sm h-[80%]"></div>
                        <div class="w-full bg-indigo-500 rounded-t-sm h-[100%] relative">
                            <div class="absolute -top-2 -right-1 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white dark:ring-slate-800"></div>
                        </div>
                        <div class="w-full bg-indigo-500/30 rounded-t-sm h-[40%]"></div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Features Grid -->
        <div id="features" class="max-w-7xl mx-auto px-6 mt-32 w-full">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 dark:text-white mb-4">{{ __('Mengapa Memilih Finance Tracker?') }}</h2>
                <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">{{ __('Kami menyediakan alat terbaik untuk membantu Anda mengatur uang tanpa pusing, mendukung gaya hidup finansial yang cerdas.') }}</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 text-left">
                <!-- Feature 1 -->
                <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 p-8 rounded-3xl hover:-translate-y-2 hover:shadow-xl dark:hover:border-indigo-500/30 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-100 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-800 dark:text-white">{{ __('Analisis Cerdas') }}</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('Visualisasikan arus kas Anda dengan grafik interaktif dan laporan komprehensif yang mudah dipahami.') }}</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 p-8 rounded-3xl hover:-translate-y-2 hover:shadow-xl dark:hover:border-emerald-500/30 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-800 dark:text-white">{{ __('Multi Dompet & Anggaran') }}</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('Pisahkan uang Anda ke berbagai rekening dan tetapkan batas anggaran bulanan agar keuangan tetap sehat.') }}</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 p-8 rounded-3xl hover:-translate-y-2 hover:shadow-xl dark:hover:border-rose-500/30 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-rose-100 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182M21.015 4.36v4.993"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-800 dark:text-white">{{ __('Transaksi Otomatis') }}</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('Jangan repot mencatat tagihan bulanan. Buat jadwal transaksi berulang dan sistem akan mencatatnya untuk Anda.') }}</p>
                </div>
            </div>
        </div>
    </main>
    
    <footer class="relative z-10 bg-white/50 dark:bg-slate-900/50 backdrop-blur-md border-t border-slate-200 dark:border-white/10 py-10 mt-auto">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-md bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="font-bold text-slate-800 dark:text-white">FinanceTracker</span>
            </div>
            <p class="text-slate-500 text-sm">&copy; {{ date('Y') }} Finance Tracker.</p>
            <div class="flex gap-4 text-sm font-medium">
                <a href="{{ route('privacy-policy') }}" class="text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{{ __('Kebijakan Privasi') }}</a>
                <a href="{{ route('terms-of-service') }}" class="text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{{ __('Ketentuan Layanan') }}</a>
            </div>
        </div>
    </footer>
</body>
</html>
