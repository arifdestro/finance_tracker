<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Finance Tracker') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
            
            /* Animated Gradient Background */
            .bg-mesh {
                background-image:
                    radial-gradient(at 0% 0%, rgba(99,102,241,0.15) 0px, transparent 50%),
                    radial-gradient(at 100% 0%, rgba(139,92,246,0.15) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, rgba(236,72,153,0.15) 0px, transparent 50%),
                    radial-gradient(at 0% 100%, rgba(56,189,248,0.15) 0px, transparent 50%);
            }
            
            /* Floating animation */
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
                100% { transform: translateY(0px); }
            }
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            .animate-float-delayed {
                animation: float 6s ease-in-out 3s infinite;
            }

            /* Glass panel */
            .glass-panel {
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.08);
            }
        </style>
    </head>
    <body class="antialiased bg-slate-950 text-white min-h-screen flex flex-col relative overflow-x-hidden">
        <!-- Background decorative elements -->
        <div class="fixed inset-0 bg-mesh z-0 pointer-events-none"></div>
        <div class="fixed top-1/4 left-1/4 w-96 h-96 bg-indigo-600/20 rounded-full mix-blend-screen filter blur-[100px] animate-float z-0 pointer-events-none"></div>
        <div class="fixed bottom-1/4 right-1/4 w-96 h-96 bg-rose-600/20 rounded-full mix-blend-screen filter blur-[100px] animate-float-delayed z-0 pointer-events-none"></div>

        <!-- Navbar -->
        <nav class="relative z-10 glass-panel border-b-0 border-white/5">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-500/25">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight">Finance<span class="text-indigo-400">Tracker</span></span>
                </div>
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-slate-300 hover:text-white transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-300 hover:text-white transition-colors">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition-all hover:scale-105">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="relative z-10 flex-1 flex flex-col items-center justify-center px-6 text-center pt-20 pb-32">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-sm font-medium mb-8">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                V1.0 is now live
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight max-w-4xl mb-8 leading-tight">
                Kendalikan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-violet-400">Keuangan</span> Anda,<br/> Wujudkan <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400">Impian</span> Anda.
            </h1>
            
            <p class="text-lg md:text-xl text-slate-400 max-w-2xl mb-12 leading-relaxed">
                Platform cerdas untuk mencatat pengeluaran, memantau anggaran, dan menganalisis tren keuangan Anda dalam satu tempat yang elegan dan aman.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center gap-4">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="px-8 py-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-lg shadow-xl shadow-indigo-500/25 transition-all hover:scale-105 hover:-translate-y-1 w-full sm:w-auto">
                        Mulai Gratis Sekarang
                    </a>
                @endif
                <a href="#features" class="px-8 py-4 rounded-xl glass-panel hover:bg-white/5 text-white font-semibold text-lg transition-all w-full sm:w-auto">
                    Pelajari Fitur
                </a>
            </div>

            <!-- Features Grid -->
            <div id="features" class="grid md:grid-cols-3 gap-6 max-w-6xl mt-32 text-left">
                <!-- Feature 1 -->
                <div class="glass-panel p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">Analisis Cerdas</h3>
                    <p class="text-slate-400 leading-relaxed">Visualisasikan arus kas Anda dengan grafik interaktif dan laporan komprehensif yang mudah dipahami.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="glass-panel p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">Multi Dompet & Anggaran</h3>
                    <p class="text-slate-400 leading-relaxed">Pisahkan uang Anda ke berbagai rekening dan tetapkan batas anggaran bulanan agar keuangan tetap sehat.</p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-panel p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182M21.015 4.36v4.993"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">Transaksi Otomatis</h3>
                    <p class="text-slate-400 leading-relaxed">Jangan repot mencatat tagihan bulanan. Buat jadwal transaksi berulang dan sistem akan mencatatnya untuk Anda.</p>
                </div>
            </div>
        </main>
        
        <footer class="relative z-10 glass-panel border-t border-white/5 py-8 mt-auto text-center">
            <p class="text-slate-500 text-sm">&copy; {{ date('Y') }} Finance Tracker. Dibangun dengan Laravel 11 & Livewire 3.</p>
        </footer>
    </body>
</html>
