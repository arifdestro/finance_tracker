<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Finance Tracker') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .bg-mesh {
                background-image:
                    radial-gradient(at 0% 0%, rgba(99,102,241,0.15) 0px, transparent 50%),
                    radial-gradient(at 100% 0%, rgba(139,92,246,0.15) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, rgba(236,72,153,0.15) 0px, transparent 50%),
                    radial-gradient(at 0% 100%, rgba(56,189,248,0.15) 0px, transparent 50%);
            }
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
                100% { transform: translateY(0px); }
            }
            .animate-float { animation: float 6s ease-in-out infinite; }
            .animate-float-delayed { animation: float 6s ease-in-out 3s infinite; }
        </style>
    </head>
    <body class="font-sans text-white antialiased selection:bg-indigo-500 selection:text-white dark">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-950 bg-mesh relative overflow-hidden">
            
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-600/20 rounded-full mix-blend-screen filter blur-[100px] animate-float pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-rose-600/20 rounded-full mix-blend-screen filter blur-[100px] animate-float-delayed pointer-events-none"></div>

            <div class="relative z-10 flex flex-col items-center w-full">
                <a href="/" wire:navigate class="mb-8 hover:scale-105 transition-transform duration-300 flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-500/25">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-white">Finance<span class="text-indigo-400">Tracker</span></span>
                </a>

                <div class="w-full sm:max-w-md px-8 py-10 bg-slate-900/80 backdrop-blur-xl shadow-2xl shadow-indigo-500/10 ring-1 ring-white/10 sm:rounded-3xl hover:shadow-indigo-500/20 transition-shadow duration-500">
                    {{ $slot }}
                </div>
                
                <div class="mt-8 text-center text-sm text-slate-500">
                    &copy; {{ date('Y') }} Finance Tracker. All rights reserved.
                </div>
            </div>
        </div>
    </body>
</html>
