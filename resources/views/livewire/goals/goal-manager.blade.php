<div>
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">{{ __('Target Tabungan') }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Kelola target finansial dan wujudkan mimpimu.') }}</p>
        </div>
        <button wire:click="openGoalModal" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ __('Buat Target Baru') }}
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($goals as $goal)
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-white/[0.06] rounded-2xl p-6 shadow-lg shadow-{{ $goal->color }}-500/5 hover:shadow-{{ $goal->color }}-500/10 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-{{ $goal->color }}-500/5 to-transparent pointer-events-none"></div>
                
                <div class="relative">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-{{ $goal->color }}-500/10 text-{{ $goal->color }}-500 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-slate-800 dark:text-white">{{ $goal->name }}</h3>
                                @if($goal->target_date)
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Target:') }} {{ $goal->target_date->format('d M Y') }}</p>
                                @else
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Tanpa tenggat waktu') }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Actions Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                            </button>
                            <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-white/10 overflow-hidden z-20" style="display: none;">
                                <button wire:click="editGoal({{ $goal->id }})" class="w-full text-left px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    {{ __('Edit Target') }}
                                </button>
                                <button onclick="confirm('{{ __('Hapus target ini? Semua dana akan dikembalikan ke dompet asal.') }}') || event.stopImmediatePropagation()" wire:click="deleteGoal({{ $goal->id }})" class="w-full text-left px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors flex items-center gap-2 border-t border-slate-100 dark:border-white/5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    {{ __('Hapus') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="flex justify-between items-end mb-2">
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Terkumpul') }}</p>
                                <p class="text-xl font-bold text-slate-800 dark:text-white">Rp {{ number_format($goal->current_amount, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Target') }}</p>
                                <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        
                        <div class="h-3 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-{{ $goal->color }}-500 to-{{ $goal->color }}-400 rounded-full transition-all duration-1000" style="width: {{ $goal->progress_percentage }}%"></div>
                        </div>
                        <p class="text-right text-xs font-bold text-{{ $goal->color }}-500 mt-1">{{ $goal->progress_percentage }}%</p>
                    </div>

                    <div class="flex gap-2 mt-4 pt-4 border-t border-slate-200 dark:border-white/10">
                        <button wire:click="openTransactionModal({{ $goal->id }}, 'deposit')" class="flex-1 py-2 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-xl text-sm font-medium transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            {{ __('Nabung') }}
                        </button>
                        <button wire:click="openTransactionModal({{ $goal->id }}, 'withdraw')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-medium transition-colors flex items-center justify-center gap-2" {{ $goal->current_amount <= 0 ? 'disabled' : '' }}>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                            {{ __('Tarik') }}
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 px-4 text-center bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm border border-dashed border-slate-300 dark:border-slate-700 rounded-3xl">
                <div class="w-20 h-20 mx-auto bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">{{ __('Belum ada target tabungan') }}</h3>
                <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-6">{{ __('Buat target finansial pertamamu dan mulailah menyisihkan uang secara rutin.') }}</p>
                <button wire:click="openGoalModal" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200">
                    {{ __('Buat Target Sekarang') }}
                </button>
            </div>
        @endforelse
    </div>

    <!-- Create/Edit Goal Modal -->
    <div x-data="{ open: @entangle('isGoalModalOpen') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/50 backdrop-blur-sm" style="display: none;">
        <div class="relative w-full max-w-md p-4 h-full md:h-auto flex items-center justify-center">
            <div @click.away="open = false" class="relative bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-white/10 w-full overflow-hidden">
                <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-white/10">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">
                        {{ $goalId ? __('Edit Target') : __('Buat Target Baru') }}
                    </h3>
                    <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg p-1.5 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6">
                    <form wire:submit.prevent="storeGoal">
                        <div class="space-y-5">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Nama Target') }}</label>
                                <input type="text" wire:model="name" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" placeholder="{{ __('Cth: Liburan ke Jepang') }}" required>
                                @error('name') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Target Jumlah (Rp)') }}</label>
                                <input type="number" wire:model="target_amount" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" placeholder="10000000" min="1" required>
                                @error('target_amount') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Batas Waktu (Opsional)') }}</label>
                                <input type="date" wire:model="target_date" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" style="color-scheme: dark;">
                                @error('target_date') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Warna Tema') }}</label>
                                <div class="flex gap-3 flex-wrap">
                                    @foreach($colors as $c)
                                        <label class="cursor-pointer">
                                            <input type="radio" wire:model="color" value="{{ $c }}" class="sr-only peer">
                                            <div class="w-10 h-10 rounded-full bg-{{ $c }}-500 peer-checked:ring-4 ring-offset-2 dark:ring-offset-slate-900 ring-{{ $c }}-500 transition-all"></div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('color') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Catatan') }}</label>
                                <textarea wire:model="notes" rows="2" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors"></textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-slate-200 dark:border-white/10">
                            <button type="button" @click="open = false" class="px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors">
                                {{ __('Batal') }}
                            </button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-500/30 transition-colors">
                                {{ __('Simpan') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction (Deposit/Withdraw) Modal -->
    <div x-data="{ open: @entangle('isTransactionModalOpen') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/50 backdrop-blur-sm" style="display: none;">
        <div class="relative w-full max-w-md p-4 h-full md:h-auto flex items-center justify-center">
            <div @click.away="open = false" class="relative bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-white/10 w-full overflow-hidden">
                <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-white/10">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">
                        {{ $transactionType === 'deposit' ? __('Nabung ke Target') : __('Tarik dari Target') }}
                    </h3>
                    <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg p-1.5 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6">
                    <form wire:submit.prevent="storeTransaction">
                        @if($goalForTransaction)
                            <div class="mb-6 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-{{ $goalForTransaction->color }}-500/10 flex items-center justify-center text-{{ $goalForTransaction->color }}-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Target') }}</p>
                                    <p class="font-bold text-slate-800 dark:text-white">{{ $goalForTransaction->name }}</p>
                                    <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">{{ __('Saldo:') }} Rp {{ number_format($goalForTransaction->current_amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-5">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                                    {{ $transactionType === 'deposit' ? __('Ambil dari Dompet') : __('Masukkan ke Dompet') }}
                                </label>
                                <select wire:model="wallet_id" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" required>
                                    <option value="">{{ __('Pilih Dompet') }}</option>
                                    @foreach($wallets as $w)
                                        <option value="{{ $w->id }}">{{ $w->name }} (Rp {{ number_format($w->balance, 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                                @error('wallet_id') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Jumlah (Rp)') }}</label>
                                <input type="number" wire:model="transactionAmount" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" placeholder="500000" min="1" required>
                                @error('transactionAmount') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Tanggal') }}</label>
                                <input type="date" wire:model="transactionDate" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" style="color-scheme: dark;" required>
                                @error('transactionDate') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Catatan (Opsional)') }}</label>
                                <input type="text" wire:model="transactionDescription" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" placeholder="{{ __('Cth: Bonus bulanan') }}">
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-slate-200 dark:border-white/10">
                            <button type="button" @click="open = false" class="px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors">
                                {{ __('Batal') }}
                            </button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white {{ $transactionType === 'deposit' ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/30' : 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-500/30' }} rounded-xl shadow-lg transition-colors">
                                {{ $transactionType === 'deposit' ? __('Simpan (Nabung)') : __('Tarik Uang') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
