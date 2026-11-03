<div>
    {{-- Header --}}
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white tracking-tight">{{ __('Transaksi Berulang') }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Otomatiskan pencatatan untuk tagihan atau pendapatan rutin Anda.') }}</p>
        </div>
        <button wire:click="create()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-lg shadow-indigo-500/25 transition-all duration-200 font-medium text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ __('Tambah Baru') }}
        </button>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-sm font-medium flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-white/[0.06] rounded-2xl shadow-lg shadow-indigo-500/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 uppercase">
                    <tr>
                        <th class="px-6 py-4 font-medium tracking-wider">{{ __('Info') }}</th>
                        <th class="px-6 py-4 font-medium tracking-wider text-right">{{ __('Jumlah') }}</th>
                        <th class="px-6 py-4 font-medium tracking-wider">{{ __('Siklus') }}</th>
                        <th class="px-6 py-4 font-medium tracking-wider">{{ __('Tgl Berikutnya') }}</th>
                        <th class="px-6 py-4 font-medium tracking-wider text-center">{{ __('Status') }}</th>
                        <th class="px-6 py-4 font-medium tracking-wider text-right">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                    @forelse($recurringTransactions as $rt)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors duration-150">
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-800 dark:text-white">{{ $rt->description }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                @if($rt->type === 'transfer')
                                    {{ __('Transfer') }}: {{ $rt->wallet->name }} &rarr; {{ $rt->toWallet?->name }}
                                @else
                                    {{ $rt->category?->name ?? __('Tanpa Kategori') }} ({{ $rt->wallet->name }})
                                @endif
                            </p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-semibold {{ $rt->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : ($rt->type === 'expense' ? 'text-rose-600 dark:text-rose-400' : 'text-blue-600 dark:text-blue-400') }}">
                            {{ $rt->type === 'income' ? '+' : ($rt->type === 'expense' ? '-' : '') }}{{ number_format($rt->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300 capitalize">
                            {{ $rt->frequency }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap {{ $rt->next_date <= now() ? 'text-rose-600 dark:text-rose-400 font-medium' : 'text-slate-600 dark:text-slate-300' }}">
                            {{ $rt->next_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button wire:click="toggleActive({{ $rt->id }})" class="px-3 py-1 rounded-full text-xs font-medium transition-all duration-200 {{ $rt->is_active ? 'bg-emerald-50 dark:bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 hover:bg-emerald-100 dark:hover:bg-emerald-500/25' : 'bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-white/10 hover:bg-slate-200 dark:hover:bg-white/10' }}">
                                {{ $rt->is_active ? __('Aktif') : __('Nonaktif') }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end gap-1">
                                <button wire:click="edit({{ $rt->id }})" class="p-2 rounded-lg text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-all duration-200" title="{{ __('Edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button wire:click="delete({{ $rt->id }})" wire:confirm="{{ __('Hapus transaksi berulang ini?') }}" class="p-2 rounded-lg text-slate-400 dark:text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-all duration-200" title="{{ __('Hapus') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800/80 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                </div>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">{{ __('Belum ada jadwal transaksi otomatis.') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($recurringTransactions->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 dark:border-white/[0.06]">
            {{ $recurringTransactions->links() }}
        </div>
        @endif
    </div>

    {{-- Modal Form --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-slate-900/20 dark:bg-black/70 backdrop-blur-sm transition-opacity" wire:click="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            {{-- Modal Content --}}
            <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-white/10">
                <form wire:submit.prevent="store">
                    <div class="px-6 pt-6 pb-6">
                        {{-- Modal Title --}}
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-white mb-6">
                            {{ $rtId ? __('Edit Transaksi Berulang') : __('Jadwalkan Transaksi Baru') }}
                        </h3>

                        {{-- Type Tabs --}}
                        <div class="flex border-b border-slate-200 dark:border-white/[0.06] mb-6">
                            <button type="button" wire:click="$set('type', 'expense')" class="flex-1 py-2.5 text-center text-sm font-medium border-b-2 transition-all duration-200 {{ $type === 'expense' ? 'border-rose-500 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300' }}">{{ __('Pengeluaran') }}</button>
                            <button type="button" wire:click="$set('type', 'income')" class="flex-1 py-2.5 text-center text-sm font-medium border-b-2 transition-all duration-200 {{ $type === 'income' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300' }}">{{ __('Pemasukan') }}</button>
                            <button type="button" wire:click="$set('type', 'transfer')" class="flex-1 py-2.5 text-center text-sm font-medium border-b-2 transition-all duration-200 {{ $type === 'transfer' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300' }}">{{ __('Transfer') }}</button>
                        </div>

                        {{-- Form Fields --}}
                        <div class="space-y-4">
                            {{-- Amount --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Jumlah') }}</label>
                                <input type="number" wire:model="amount" class="w-full rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200">
                                @error('amount') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Wallet & Category/To Wallet --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ $type === 'transfer' ? __('Dari Dompet') : __('Dompet') }}</label>
                                    <select wire:model="wallet_id" class="w-full rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200">
                                        <option value="">{{ __('Pilih Dompet') }}</option>
                                        @foreach($wallets as $wallet)
                                            <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('wallet_id') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                @if($type === 'transfer')
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Ke Dompet') }}</label>
                                    <select wire:model="to_wallet_id" class="w-full rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200">
                                        <option value="">{{ __('Pilih Tujuan') }}</option>
                                        @foreach($wallets as $wallet)
                                            <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('to_wallet_id') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                @else
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Kategori') }}</label>
                                    <select wire:model="category_id" class="w-full rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200">
                                        <option value="">{{ __('Pilih Kategori') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                @endif
                            </div>

                            {{-- Description --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Deskripsi Tagihan/Pendapatan') }}</label>
                                <input type="text" wire:model="description" placeholder="{{ __('Contoh: Tagihan Listrik Bulanan') }}" class="w-full rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200">
                                @error('description') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Frequency & Next Date --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Siklus') }}</label>
                                    <select wire:model="frequency" class="w-full rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200">
                                        <option value="daily">{{ __('Harian') }}</option>
                                        <option value="weekly">{{ __('Mingguan') }}</option>
                                        <option value="monthly">{{ __('Bulanan') }}</option>
                                        <option value="yearly">{{ __('Tahunan') }}</option>
                                    </select>
                                    @error('frequency') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Tgl Pemrosesan Berikutnya') }}</label>
                                    <input type="date" wire:model="next_date" class="w-full rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200">
                                    @error('next_date') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Active Toggle --}}
                            <div class="flex items-center mt-4 pt-2">
                                <input id="is_active" type="checkbox" wire:model="is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500/50 border-slate-300 dark:border-white/10 rounded bg-white dark:bg-slate-800/50">
                                <label for="is_active" class="ml-2.5 block text-sm text-slate-700 dark:text-slate-300">
                                    {{ __('Status Aktif') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl border-t border-slate-200 dark:border-white/[0.06]">
                        <button type="button" wire:click="closeModal()" class="px-4 py-2.5 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/10 rounded-xl transition-all duration-200 text-sm font-medium">
                            {{ __('Batal') }}
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-lg shadow-indigo-500/25 transition-all duration-200 text-sm font-medium">
                            {{ __('Simpan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
