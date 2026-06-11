<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased selection:bg-indigo-500 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50 dark:bg-gray-900 relative overflow-hidden">
            
            <!-- Decorative background elements -->
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-violet-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col items-center">
                <a href="/" wire:navigate class="mb-8 hover:scale-105 transition-transform duration-300">
                    <x-application-logo />
                </a>

                <div class="w-full sm:max-w-md px-8 py-10 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl shadow-2xl shadow-indigo-500/5 ring-1 ring-gray-200 dark:ring-gray-700/50 overflow-hidden sm:rounded-2xl">
                    {{ $slot }}
                </div>
                
                <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    &copy; {{ date('Y') }} Finance Tracker. All rights reserved.
                </div>
            </div>
        </div>
    </body>
</html>
