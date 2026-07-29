<div>
    {{-- Header --}}
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">{{ __('Daftar Dompet & Rekening') }}</h1>
            <p class="mt-1 text-sm text-slate-400">{{ __('Kelola sumber dana untuk transaksi Anda.') }}</p>
        </div>
        <button wire:click="create()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-lg shadow-indigo-500/25 transition-all duration-200 font-medium text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ __('Tambah Dompet') }}
        </button>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    {{-- Wallet Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($wallets as $wallet)
            <div class="bg-slate-900/80 backdrop-blur-xl border border-white/[0.06] rounded-2xl p-6 shadow-lg shadow-indigo-500/5 relative overflow-hidden group text-white hover:scale-[1.01] transition-all duration-200 hover:border-white/[0.12]">
                {{-- Decorative Glow Circle --}}
                <div class="absolute -right-10 -top-10 w-36 h-36 rounded-full opacity-15 blur-3xl transition-opacity duration-300 group-hover:opacity-25" style="background-color: {{ $wallet->color }}"></div>
                <div class="absolute -left-16 -bottom-16 w-32 h-32 rounded-full opacity-10 blur-3xl" style="background-color: {{ $wallet->color }}"></div>

                {{-- Card Header --}}
                <div class="flex justify-between items-start mb-6 relative z-10">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2.5">
                            <div class="w-3 h-3 rounded-full ring-2 ring-white/10 flex-shrink-0" style="background-color: {{ $wallet->color }}"></div>
                            <h3 class="font-bold text-lg text-white truncate">{{ $wallet->name }}</h3>
                            @if($wallet->is_default)
                                <span class="px-2.5 py-0.5 bg-amber-500/15 text-amber-400 text-xs font-medium rounded-full border border-amber-500/20 flex-shrink-0">{{ __('Utama') }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 uppercase tracking-widest mt-2 font-medium">{{ $wallet->type }}</p>
                    </div>

                    {{-- Action Buttons (visible on hover) --}}
                    <div class="opacity-0 group-hover:opacity-100 transition-all duration-200 flex gap-1.5 z-10 flex-shrink-0 ml-2">
                        <button wire:click="edit({{ $wallet->id }})" class="p-2 bg-white/5 hover:bg-white/10 rounded-xl transition-all duration-200 text-slate-400 hover:text-white" title="{{ __('Edit') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <button wire:click="delete({{ $wallet->id }})" class="p-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 rounded-xl transition-all duration-200" title="{{ __('Hapus') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>

                {{-- Balance --}}
                <div class="mt-4 relative z-10">
                    <p class="text-xs text-slate-500 mb-1.5 font-medium uppercase tracking-wider">{{ __('Saldo') }}</p>
                    <p class="text-3xl font-bold tracking-tight text-white">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</p>
                </div>

                {{-- Bottom accent line --}}
                <div class="absolute bottom-0 left-0 right-0 h-[2px] opacity-30" style="background: linear-gradient(90deg, transparent, {{ $wallet->color }}, transparent)"></div>
            </div>
        @endforeach
    </div>

    {{-- Modal Form --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity" wire:click="closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            {{-- Modal Panel --}}
            <div class="inline-block align-bottom bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/10">
                <form wire:submit.prevent="store">
                    <div class="px-6 pt-6 pb-6">
                        {{-- Modal Title --}}
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <h3 class="text-xl font-semibold text-white">
                                {{ $walletId ? __('Edit Dompet') : __('Tambah Dompet Baru') }}
                            </h3>
                        </div>

                        <div class="space-y-5">
                            {{-- Wallet Name --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('Nama Dompet / Rekening') }}</label>
                                <input type="text" wire:model="name" placeholder="{{ __('Contoh: BCA, GoPay, Tunai...') }}" class="w-full rounded-xl bg-slate-800/50 border border-white/10 text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 px-4 py-2.5 text-sm transition-all duration-200">
                                @error('name') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Type & Color --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('Tipe') }}</label>
                                    <select wire:model="type" class="w-full rounded-xl bg-slate-800/50 border border-white/10 text-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 px-4 py-2.5 text-sm transition-all duration-200">
                                        <option value="cash">{{ __('Tunai (Cash)') }}</option>
                                        <option value="bank">{{ __('Rekening Bank') }}</option>
                                        <option value="e-wallet">{{ __('E-Wallet') }}</option>
                                    </select>
                                    @error('type') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('Warna Kartu') }}</label>
                                    <input type="color" wire:model="color" class="w-full h-[42px] rounded-xl bg-slate-800/50 border border-white/10 cursor-pointer p-1.5 transition-all duration-200">
                                </div>
                            </div>

                            {{-- Initial Balance --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('Saldo Awal') }}</label>
                                <div class="relative"
                                    x-data="{
                                        display: '',
                                        init() {
                                            let val = $wire.balance;
                                            if (val) this.display = Number(val).toLocaleString('id-ID');
                                        },
                                        format(e) {
                                            let raw = e.target.value.replace(/\D/g, '');
                                            this.display = raw ? Number(raw).toLocaleString('id-ID') : '';
                                            $wire.set('balance', raw ? Number(raw) : '');
                                        }
                                    }"
                                    x-init="init()">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-slate-500 text-sm font-medium">Rp</span>
                                    </div>
                                    <input
                                        type="text"
                                        inputmode="numeric"
                                        x-model="display"
                                        @input="format($event)"
                                        placeholder="0"
                                        class="w-full pl-12 rounded-xl bg-slate-800/50 border border-white/10 text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 px-4 py-2.5 text-sm transition-all duration-200"
                                    >
                                </div>
                                @error('balance') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Default Checkbox --}}
                            <div class="flex items-center mt-2 group/check">
                                <input id="is_default" type="checkbox" wire:model="is_default" class="h-4 w-4 text-indigo-600 bg-slate-800/50 border-white/10 rounded focus:ring-indigo-500/50 focus:ring-offset-0 transition-all duration-200">
                                <label for="is_default" class="ml-2.5 block text-sm text-slate-300 group-hover/check:text-white transition-colors cursor-pointer">
                                    {{ __('Jadikan sebagai dompet utama') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-slate-800/50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl border-t border-white/[0.06]">
                        <button type="button" wire:click="closeModal()" class="px-4 py-2.5 bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 rounded-xl transition-all duration-200 text-sm font-medium">
                            {{ __('Batal') }}
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-lg shadow-indigo-500/25 transition-all duration-200 text-sm font-medium">
                            {{ __('Simpan Dompet') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
