<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengaturan</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola preferensi akun dan tampilan aplikasi Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Account Settings -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Profil Akun</h2>
                <div class="flex items-center gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="w-16 h-16 rounded-full bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-2xl">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">{{ auth()->user()->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                
                <a href="{{ route('profile') }}" wire:navigate class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm font-medium">
                    Edit Profil & Password
                </a>
            </div>

            <!-- Application Settings -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Preferensi Aplikasi</h2>
                
                <div class="space-y-4">
                    <div x-data="{ 
                            darkMode: document.documentElement.classList.contains('dark'),
                            toggle() {
                                this.darkMode = !this.darkMode;
                                if(this.darkMode) {
                                    document.documentElement.classList.add('dark');
                                    localStorage.theme = 'dark';
                                } else {
                                    document.documentElement.classList.remove('dark');
                                    localStorage.theme = 'light';
                                }
                            }
                        }" 
                        class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-700">
                        <div>
                            <h3 class="font-medium text-gray-900 dark:text-white">Mode Gelap (Dark Mode)</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Ubah tampilan aplikasi menjadi tema gelap.</p>
                        </div>
                        <button @click="toggle()" :class="darkMode ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-600'" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none">
                            <span aria-hidden="true" :class="darkMode ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-700 opacity-50 cursor-not-allowed">
                        <div>
                            <h3 class="font-medium text-gray-900 dark:text-white">Bahasa Aplikasi</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Saat ini hanya tersedia dalam Bahasa Indonesia.</p>
                        </div>
                        <span class="text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 px-3 py-1 rounded-md border border-gray-200 dark:border-gray-600">ID</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tentang Aplikasi</h2>
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <ul class="space-y-3 text-sm">
                    <li class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500 dark:text-gray-400">Versi Aplikasi</span>
                        <span class="font-semibold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">v1.0.0</span>
                    </li>
                    <li class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500 dark:text-gray-400">Framework</span>
                        <span class="font-medium text-gray-900 dark:text-white">Laravel v{{ Illuminate\Foundation\Application::VERSION }}</span>
                    </li>
                    <li class="flex justify-between items-center py-2">
                        <span class="text-gray-500 dark:text-gray-400">PHP Version</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ PHP_VERSION }}</span>
                    </li>
                </ul>
                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 text-center">
                    <p class="text-xs text-gray-400">&copy; {{ date('Y') }} Finance Tracker by Antigravity.</p>
                </div>
            </div>
        </div>

    </div>
</div>
