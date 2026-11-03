<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Ketentuan Layanan') }} - {{ config('app.name', 'Finance Tracker') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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
        body { font-family: 'Inter', sans-serif; }
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
        .prose-custom h2 { scroll-margin-top: 6rem; }
    </style>
</head>
<body class="antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-300 min-h-screen flex flex-col relative overflow-x-hidden">
    <div class="fixed inset-0 bg-mesh z-0 pointer-events-none transition-colors duration-300"></div>

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border-b border-slate-200 dark:border-white/10 transition-colors duration-300">
        <div class="max-w-5xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-500/25">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-slate-800 dark:text-white">Finance<span class="text-indigo-600 dark:text-indigo-400">Tracker</span></span>
            </a>
            <a href="/" class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-white transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Kembali') }}
            </a>
        </div>
    </nav>

    <!-- Content -->
    <main class="relative z-10 flex-1 max-w-5xl mx-auto w-full px-6 py-16">
        <!-- Header -->
        <div class="mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-100 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 text-indigo-700 dark:text-indigo-300 text-sm font-medium mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                {{ __('Dokumen Hukum') }}
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 text-slate-800 dark:text-white">{{ __('Ketentuan Layanan') }}</h1>
            <p class="text-slate-500 dark:text-slate-400 text-lg">{{ __('Terakhir diperbarui:') }} {{ now()->translatedFormat('d F Y') }}</p>
        </div>

        <!-- Sections -->
        <div class="space-y-8 prose-custom">
            
            <section class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-sm font-bold">1</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">{{ __('Penerimaan Ketentuan') }}</h2>
                </div>
                <div class="text-slate-600 dark:text-slate-400 leading-relaxed space-y-4">
                    <p>{{ __('Dengan mendaftar, mengakses, atau menggunakan layanan aplikasi Finance Tracker, Anda menyetujui untuk terikat oleh Ketentuan Layanan ini. Jika Anda tidak menyetujui sebagian atau seluruh ketentuan ini, Anda dilarang menggunakan layanan kami.') }}</p>
                </div>
            </section>

            <section class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 text-sm font-bold">2</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">{{ __('Penggunaan Layanan') }}</h2>
                </div>
                <div class="text-slate-600 dark:text-slate-400 leading-relaxed space-y-4">
                    <p>{{ __('Anda setuju untuk menggunakan layanan kami hanya untuk tujuan yang sah, yakni mencatat dan menganalisis data keuangan pribadi atau bisnis skala kecil Anda. Anda tidak diperbolehkan:') }}</p>
                    <ul class="list-none space-y-2 ml-0">
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                            <span>{{ __('Mengeksploitasi celah keamanan atau mencoba meretas sistem kami.') }}</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                            <span>{{ __('Menyalahgunakan fitur layanan untuk tujuan komersial di luar kewajaran.') }}</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                            <span>{{ __('Memalsukan identitas atau menggunakan layanan menggunakan data pihak lain tanpa izin.') }}</span>
                        </li>
                    </ul>
                </div>
            </section>

            <section class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-rose-100 dark:bg-rose-500/20 flex items-center justify-center text-rose-600 dark:text-rose-400 text-sm font-bold">3</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">{{ __('Akun dan Keamanan') }}</h2>
                </div>
                <div class="text-slate-600 dark:text-slate-400 leading-relaxed space-y-4">
                    <p>{{ __('Anda bertanggung jawab penuh untuk menjaga kerahasiaan kata sandi akun Anda. Segala aktivitas yang terjadi di bawah akun Anda adalah tanggung jawab Anda sepenuhnya. Kami sangat menyarankan agar Anda menggunakan kata sandi yang kuat (kombinasi huruf, angka, dan simbol).') }}</p>
                </div>
            </section>
            
            <section class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center text-violet-600 dark:text-violet-400 text-sm font-bold">4</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">{{ __('Batasan Tanggung Jawab') }}</h2>
                </div>
                <div class="text-slate-600 dark:text-slate-400 leading-relaxed space-y-4">
                    <p>{{ __('Aplikasi ini disediakan secara \'sebagaimana adanya\' (as is). Meskipun kami berusaha sebaik mungkin memastikan keakuratan perhitungan, kami tidak menjamin bahwa layanan akan selalu bebas dari kesalahan (bug) atau tidak terganggu. Kami tidak bertanggung jawab atas kerugian finansial, kehilangan data, atau masalah apa pun yang diakibatkan oleh penggunaan aplikasi ini.') }}</p>
                </div>
            </section>

            <section class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 text-sm font-bold">5</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">{{ __('Pemutusan Layanan') }}</h2>
                </div>
                <div class="text-slate-600 dark:text-slate-400 leading-relaxed space-y-4">
                    <p>{{ __('Kami berhak, atas kebijakan kami sendiri, menangguhkan atau menghentikan akses Anda ke aplikasi sewaktu-waktu tanpa pemberitahuan sebelumnya, jika kami menemukan adanya pelanggaran terhadap Ketentuan Layanan ini.') }}</p>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 bg-white/50 dark:bg-slate-900/50 backdrop-blur-md border-t border-slate-200 dark:border-white/10 py-8 mt-auto text-center">
        <p class="text-slate-500 text-sm">&copy; {{ date('Y') }} Finance Tracker.</p>
    </footer>
</body>
</html>
