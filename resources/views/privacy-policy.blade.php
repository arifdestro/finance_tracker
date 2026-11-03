<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kebijakan Privasi - {{ config('app.name', 'Finance Tracker') }}</title>
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
                Kembali
            </a>
        </div>
    </nav>

    <!-- Content -->
    <main class="relative z-10 flex-1 max-w-5xl mx-auto w-full px-6 py-16">
        <!-- Header -->
        <div class="mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-100 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 text-indigo-700 dark:text-indigo-300 text-sm font-medium mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Dokumen Hukum
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 text-slate-800 dark:text-white">Kebijakan Privasi</h1>
            <p class="text-slate-500 dark:text-slate-400 text-lg">Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}</p>
        </div>

        <!-- Table of Contents -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 rounded-2xl p-6 mb-10">
            <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-4">Daftar Isi</h3>
            <nav class="grid sm:grid-cols-2 gap-2">
                <a href="#informasi-yang-kami-kumpulkan" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors py-1">1. Informasi yang Kami Kumpulkan</a>
                <a href="#penggunaan-informasi" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors py-1">2. Penggunaan Informasi</a>
                <a href="#penyimpanan-data" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors py-1">3. Penyimpanan Data</a>
                <a href="#keamanan-data" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors py-1">4. Keamanan Data</a>
                <a href="#layanan-pihak-ketiga" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors py-1">5. Layanan Pihak Ketiga</a>
                <a href="#hak-pengguna" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors py-1">6. Hak Pengguna</a>
                <a href="#cookie" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors py-1">7. Cookie</a>
                <a href="#perubahan-kebijakan" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors py-1">8. Perubahan Kebijakan</a>
                <a href="#hubungi-kami" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors py-1">9. Hubungi Kami</a>
            </nav>
        </div>

        <!-- Sections -->
        <div class="space-y-8 prose-custom">
            <!-- Section 1 -->
            <section id="informasi-yang-kami-kumpulkan" class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-sm font-bold">1</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Informasi yang Kami Kumpulkan</h2>
                </div>
                <div class="text-slate-600 dark:text-slate-400 leading-relaxed space-y-4">
                    <p>Kami mengumpulkan informasi yang Anda berikan secara langsung saat mendaftar dan menggunakan layanan kami, termasuk:</p>
                    <ul class="list-none space-y-2 ml-0">
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-2 shrink-0"></span>
                            <span><strong class="text-slate-800 dark:text-white">Informasi Akun:</strong> Nama, alamat email, dan kata sandi (atau kredensial Google jika menggunakan login Google).</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-2 shrink-0"></span>
                            <span><strong class="text-slate-800 dark:text-white">Data Keuangan:</strong> Data transaksi, kategori, dompet, anggaran, dan transaksi berulang yang Anda masukkan ke dalam aplikasi.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-2 shrink-0"></span>
                            <span><strong class="text-slate-800 dark:text-white">Informasi Preferensi:</strong> Pengaturan tampilan (tema gelap/terang), mata uang, dan bahasa.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <!-- Section 2 -->
            <section id="penggunaan-informasi" class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 text-sm font-bold">2</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Penggunaan Informasi</h2>
                </div>
                <div class="text-slate-600 dark:text-slate-400 leading-relaxed space-y-4">
                    <p>Informasi yang dikumpulkan digunakan untuk:</p>
                    <ul class="list-none space-y-2 ml-0">
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                            <span>Menyediakan, mengoperasikan, dan memelihara layanan Finance Tracker.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                            <span>Menghasilkan laporan keuangan, grafik, dan analisis berdasarkan data yang Anda masukkan.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                            <span>Mengirimkan notifikasi terkait aktivitas akun atau perubahan layanan.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                            <span>Meningkatkan pengalaman pengguna dan mengembangkan fitur baru.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <!-- Section 3 -->
            <section id="penyimpanan-data" class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center text-violet-600 dark:text-violet-400 text-sm font-bold">3</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Penyimpanan Data</h2>
                </div>
                <div class="text-slate-600 dark:text-slate-400 leading-relaxed space-y-4">
                    <p>Data Anda disimpan pada server yang aman dan dilindungi. Kami menyimpan data selama akun Anda aktif atau selama diperlukan untuk menyediakan layanan. Anda dapat meminta penghapusan data kapan saja melalui pengaturan akun atau dengan menghubungi kami.</p>
                </div>
            </section>

            <!-- Section 4 -->
            <section id="keamanan-data" class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 text-sm font-bold">4</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Keamanan Data</h2>
                </div>
                <div class="text-slate-600 dark:text-slate-400 leading-relaxed space-y-4">
                    <p>Kami mengimplementasikan langkah-langkah keamanan yang sesuai standar industri untuk melindungi data Anda, termasuk:</p>
                    <ul class="list-none space-y-2 ml-0">
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mt-2 shrink-0"></span>
                            <span>Enkripsi kata sandi menggunakan algoritma bcrypt.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mt-2 shrink-0"></span>
                            <span>Proteksi CSRF (Cross-Site Request Forgery) pada semua formulir.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mt-2 shrink-0"></span>
                            <span>Validasi dan sanitasi input pengguna untuk mencegah injeksi.</span>
                        </li>
                    </ul>
                    <p>Meskipun demikian, tidak ada metode transmisi data melalui internet yang 100% aman. Kami tidak dapat menjamin keamanan mutlak data Anda.</p>
                </div>
            </section>

            <!-- Section 5 -->
            <section id="layanan-pihak-ketiga" class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-rose-100 dark:bg-rose-500/20 flex items-center justify-center text-rose-600 dark:text-rose-400 text-sm font-bold">5</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Layanan Pihak Ketiga</h2>
                </div>
                <div class="text-slate-600 dark:text-slate-400 leading-relaxed space-y-4">
                    <p>Kami menggunakan layanan pihak ketiga berikut:</p>
                    <ul class="list-none space-y-2 ml-0">
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mt-2 shrink-0"></span>
                            <span><strong class="text-slate-800 dark:text-white">Google OAuth:</strong> Untuk otentikasi login menggunakan akun Google. Data yang diterima meliputi nama, email, dan foto profil dari akun Google Anda.</span>
                        </li>
                    </ul>
                    <p>Kami tidak menjual, memperdagangkan, atau mentransfer data pribadi Anda kepada pihak luar tanpa persetujuan Anda.</p>
                </div>
            </section>

            <!-- Section 6 -->
            <section id="hak-pengguna" class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-cyan-100 dark:bg-cyan-500/20 flex items-center justify-center text-cyan-600 dark:text-cyan-400 text-sm font-bold">6</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Hak Pengguna</h2>
                </div>
                <div class="text-slate-600 dark:text-slate-400 leading-relaxed space-y-4">
                    <p>Anda memiliki hak untuk:</p>
                    <ul class="list-none space-y-2 ml-0">
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 mt-2 shrink-0"></span>
                            <span><strong class="text-slate-800 dark:text-white">Mengakses</strong> data pribadi yang kami simpan tentang Anda.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 mt-2 shrink-0"></span>
                            <span><strong class="text-slate-800 dark:text-white">Memperbarui</strong> informasi akun Anda melalui halaman profil.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 mt-2 shrink-0"></span>
                            <span><strong class="text-slate-800 dark:text-white">Mengekspor</strong> seluruh data keuangan Anda dalam format JSON melalui fitur Backup.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 mt-2 shrink-0"></span>
                            <span><strong class="text-slate-800 dark:text-white">Menghapus</strong> akun dan seluruh data terkait dengan menghubungi kami.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <!-- Section 7 -->
            <section id="cookie" class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-orange-100 dark:bg-orange-500/20 flex items-center justify-center text-orange-600 dark:text-orange-400 text-sm font-bold">7</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Cookie</h2>
                </div>
                <div class="text-slate-600 dark:text-slate-400 leading-relaxed space-y-4">
                    <p>Kami menggunakan cookie untuk:</p>
                    <ul class="list-none space-y-2 ml-0">
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500 mt-2 shrink-0"></span>
                            <span>Menjaga sesi login Anda agar tetap aktif.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500 mt-2 shrink-0"></span>
                            <span>Menyimpan preferensi tampilan (seperti tema gelap/terang) di perangkat Anda.</span>
                        </li>
                    </ul>
                    <p>Cookie ini bersifat esensial dan diperlukan untuk operasional layanan. Kami tidak menggunakan cookie pelacakan pihak ketiga.</p>
                </div>
            </section>

            <!-- Section 8 -->
            <section id="perubahan-kebijakan" class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-pink-100 dark:bg-pink-500/20 flex items-center justify-center text-pink-600 dark:text-pink-400 text-sm font-bold">8</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Perubahan Kebijakan</h2>
                </div>
                <div class="text-slate-600 dark:text-slate-400 leading-relaxed space-y-4">
                    <p>Kami berhak memperbarui kebijakan privasi ini dari waktu ke waktu. Perubahan signifikan akan diberitahukan melalui email atau pemberitahuan dalam aplikasi. Penggunaan layanan yang berkelanjutan setelah perubahan dianggap sebagai persetujuan Anda terhadap kebijakan yang diperbarui.</p>
                </div>
            </section>

            <!-- Section 9 -->
            <section id="hubungi-kami" class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-lg border border-slate-200 dark:border-white/5 rounded-3xl p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-teal-100 dark:bg-teal-500/20 flex items-center justify-center text-teal-600 dark:text-teal-400 text-sm font-bold">9</span>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Hubungi Kami</h2>
                </div>
                <div class="text-slate-600 dark:text-slate-400 leading-relaxed space-y-4">
                    <p>Jika Anda memiliki pertanyaan atau kekhawatiran mengenai kebijakan privasi ini, silakan hubungi kami melalui:</p>
                    <div class="bg-white dark:bg-slate-800/50 rounded-xl p-5 flex items-center gap-4 border border-slate-100 dark:border-white/5">
                        <div class="w-10 h-10 rounded-xl bg-teal-100 dark:bg-teal-500/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Email</p>
                            <p class="text-slate-800 dark:text-white font-medium">support@financetracker.com</p>
                        </div>
                    </div>
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
