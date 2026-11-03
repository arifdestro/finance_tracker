<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ __('Pengaturan') }}</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Kelola preferensi akun dan tampilan aplikasi Anda.') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Account Settings -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-slate-200 dark:border-white/[0.06] p-6 shadow-lg shadow-indigo-500/5">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4">{{ __('Profil Akun') }}</h2>
                <div class="flex items-center gap-4 mb-6 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-white/10">
                    <div class="w-16 h-16 rounded-full bg-indigo-500/10 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-2xl ring-2 ring-indigo-500/20">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">{{ auth()->user()->name }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                
                <a href="{{ route('profile') }}" wire:navigate class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-lg shadow-indigo-500/25 transition-all duration-200 font-medium">
                    {{ __('Edit Profil & Password') }}
                </a>
            </div>

            <!-- Application Settings -->
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-slate-200 dark:border-white/[0.06] p-6 shadow-lg shadow-indigo-500/5">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4">{{ __('Preferensi Aplikasi') }}</h2>
                
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
                        x-on:livewire:navigated.window="darkMode = document.documentElement.classList.contains('dark')"
                        class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-white/10 hover:bg-slate-100 dark:hover:bg-white/[0.04] transition-colors duration-200">
                        <div>
                            <h3 class="font-medium text-slate-800 dark:text-white">{{ __('Mode Gelap (Dark Mode)') }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Ubah tampilan aplikasi menjadi tema gelap.') }}</p>
                        </div>
                        <button @click="toggle()" :class="darkMode ? 'bg-indigo-600' : 'bg-slate-300 dark:bg-slate-600'" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:ring-offset-2 focus:ring-offset-slate-900">
                            <span aria-hidden="true" :class="darkMode ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-white/10 hover:bg-slate-100 dark:hover:bg-white/[0.04] transition-colors duration-200 cursor-pointer" wire:click="toggleLanguage">
                        <div>
                            <h3 class="font-medium text-slate-800 dark:text-white">{{ __('Bahasa Aplikasi') }} / Language</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Ganti bahasa aplikasi (ID / EN).') }}</p>
                        </div>
                        <button class="{{ auth()->user()->locale === 'en' ? 'bg-indigo-600' : 'bg-slate-300 dark:bg-slate-600' }} relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:ring-offset-2 focus:ring-offset-slate-900">
                            <span aria-hidden="true" class="{{ auth()->user()->locale === 'en' ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="space-y-6">
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-slate-200 dark:border-white/[0.06] p-6 shadow-lg shadow-indigo-500/5">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4">{{ __('Tentang Aplikasi') }}</h2>
                <div class="flex justify-center mb-6">
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl blur-md opacity-60 group-hover:opacity-80 transition-opacity duration-300"></div>
                        <div class="relative w-20 h-20 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-2xl shadow-indigo-500/30">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
                <ul class="space-y-3 text-sm">
                    <li class="flex justify-between items-center py-2 border-b border-slate-200 dark:border-white/[0.06]">
                        <span class="text-slate-500 dark:text-slate-400">{{ __('Versi Aplikasi') }}</span>
                        <span class="font-semibold text-indigo-700 dark:text-indigo-400 bg-indigo-500/10 px-2.5 py-0.5 rounded-full text-xs">v1.0.0</span>
                    </li>
                    <li class="flex justify-between items-center py-2 border-b border-slate-200 dark:border-white/[0.06]">
                        <span class="text-slate-500 dark:text-slate-400">Framework</span>
                        <span class="font-medium text-slate-800 dark:text-white">Laravel v{{ Illuminate\Foundation\Application::VERSION }}</span>
                    </li>
                    <li class="flex justify-between items-center py-2">
                        <span class="text-slate-500 dark:text-slate-400">PHP Version</span>
                        <span class="font-medium text-slate-800 dark:text-white">{{ PHP_VERSION }}</span>
                    </li>
                </ul>
                <div class="mt-6 pt-4 border-t border-slate-200 dark:border-white/[0.06] text-center">
                    <p class="text-xs text-slate-400 dark:text-slate-500">&copy; {{ date('Y') }} Finance Tracker.</p>
                </div>
            </div>
        </div>

    </div>
</div>
