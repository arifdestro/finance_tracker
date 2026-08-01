<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">{{ __('Portofolio Aset') }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Pantau investasi Kripto, Saham, Emas, dan Valas Anda di satu tempat.') }}</p>
        </div>
        <button wire:click="openModal" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ __('Tambah Aset') }}
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    <!-- Portfolio Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-indigo-600 to-violet-600 dark:from-indigo-900/80 dark:to-slate-900/80 backdrop-blur-xl rounded-3xl p-6 text-white shadow-xl shadow-indigo-500/20 border border-transparent dark:border-indigo-500/20 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-8 -mr-8 w-40 h-40 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10">
                <p class="text-indigo-100 font-medium mb-1">{{ __('Total Nilai Portofolio') }}</p>
                <h2 class="text-3xl font-bold text-white mb-2">Rp {{ number_format($totalNetWorth, 0, ',', '.') }}</h2>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $totalRoiPercentage >= 0 ? 'bg-emerald-500/30 text-white' : 'bg-rose-500/30 text-white' }}">
                        {{ $totalRoiPercentage > 0 ? '+' : '' }}{{ number_format($totalRoiPercentage, 2) }}% ROI
                    </span>
                    <span class="text-xs text-indigo-100/80">{{ __('Total Keuntungan: ') }} <span class="font-semibold {{ $totalProfitLoss >= 0 ? 'text-emerald-300' : 'text-rose-300' }}">{{ $totalProfitLoss > 0 ? '+' : '' }}Rp {{ number_format($totalProfitLoss, 0, ',', '.') }}</span></span>
                </div>
            </div>
        </div>
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-white/[0.06] rounded-3xl p-6 shadow-lg flex items-center justify-between">
            <div>
                <p class="text-slate-500 dark:text-slate-400 font-medium mb-1">{{ __('Total Modal (Cost)') }}</p>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Rp {{ number_format($totalCost, 0, ',', '.') }}</h2>
            </div>
            <div class="w-12 h-12 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-white/[0.06] rounded-3xl p-6 shadow-lg flex items-center justify-between">
            <div>
                <p class="text-slate-500 dark:text-slate-400 font-medium mb-1">{{ __('Jumlah Aset') }}</p>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $assets->count() }} <span class="text-sm font-normal text-slate-500">{{ __('Item') }}</span></h2>
            </div>
            <div class="w-12 h-12 bg-sky-500/10 rounded-2xl flex items-center justify-center text-sky-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
        </div>
    </div>

    <!-- Assets List -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($assets as $asset)
            @php
                $isProfit = $asset->profit_loss >= 0;
            @endphp
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-white/[0.06] rounded-3xl p-6 shadow-lg relative group transition-all hover:shadow-{{ $asset->color }}-500/10 hover:border-{{ $asset->color }}-500/30">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-{{ $asset->color }}-500/10 text-{{ $asset->color }}-500 flex items-center justify-center">
                            @if($asset->type === 'crypto')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            @elseif($asset->type === 'stock')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                            @elseif($asset->type === 'gold')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-slate-800 dark:text-white leading-tight">{{ $asset->name }}</h3>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $asset->symbol }} &bull; {{ ucfirst(str_replace('_', ' ', $asset->type)) }}</p>
                        </div>
                    </div>
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-40 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-white/10 overflow-hidden z-20" style="display: none;">
                            <button wire:click="edit({{ $asset->id }})" class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">{{ __('Edit') }}</button>
                            <button wire:click="delete({{ $asset->id }})" class="w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors border-t border-slate-100 dark:border-white/5">{{ __('Hapus') }}</button>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-3xl font-bold text-slate-800 dark:text-white">Rp {{ number_format($asset->total_value, 0, ',', '.') }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-sm font-semibold {{ $isProfit ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            {{ $isProfit ? '+' : '' }}Rp {{ number_format($asset->profit_loss, 0, ',', '.') }} ({{ $isProfit ? '+' : '' }}{{ number_format($asset->roi_percentage, 2) }}%)
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl p-3 border border-slate-100 dark:border-white/5 mb-4 text-sm">
                    <div>
                        <p class="text-slate-500 dark:text-slate-400 text-[11px]">{{ __('Jumlah (Qty)') }}</p>
                        <p class="font-medium text-slate-800 dark:text-white">{{ rtrim(rtrim(number_format($asset->quantity, 8, '.', ''), '0'), '.') }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400 text-[11px]">{{ __('Modal Avg.') }}</p>
                        <p class="font-medium text-slate-800 dark:text-white">Rp {{ number_format($asset->average_buy_price, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-slate-100 dark:border-white/5 pt-4">
                    <div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-0.5">{{ __('Harga Pasar (Current)') }}</p>
                        <p class="font-semibold text-slate-700 dark:text-slate-300">Rp {{ number_format($asset->current_price, 0, ',', '.') }}</p>
                    </div>
                    <button wire:click="openUpdatePriceModal({{ $asset->id }})" class="px-3 py-1.5 bg-{{ $asset->color }}-500/10 hover:bg-{{ $asset->color }}-500/20 text-{{ $asset->color }}-600 dark:text-{{ $asset->color }}-400 text-xs font-medium rounded-lg transition-colors flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        {{ __('Update') }}
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 px-4 text-center bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm border border-dashed border-slate-300 dark:border-slate-700 rounded-3xl">
                <div class="w-20 h-20 mx-auto bg-indigo-50 dark:bg-indigo-500/10 rounded-full flex items-center justify-center mb-4 text-indigo-500">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">{{ __('Belum ada Portofolio Aset') }}</h3>
                <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-6">{{ __('Mulai catat investasi Anda (Kripto, Saham, Reksadana) dan lacak persentase keuntungan (ROI) secara otomatis.') }}</p>
                <button wire:click="openModal" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200">
                    {{ __('Tambah Aset') }}
                </button>
            </div>
        @endforelse
    </div>

    <!-- Create/Edit Modal -->
    <div x-data="{ open: @entangle('isModalOpen') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/50 backdrop-blur-sm" style="display: none;">
        <div class="relative w-full max-w-md p-4 h-full md:h-auto flex items-center justify-center">
            <div @click.away="open = false" class="relative bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-white/10 w-full overflow-hidden max-h-[90vh] overflow-y-auto custom-scrollbar">
                <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-white/10 sticky top-0 bg-white/90 dark:bg-slate-900/90 backdrop-blur z-10">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">
                        {{ $assetId ? __('Edit Aset') : __('Tambah Aset') }}
                    </h3>
                    <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg p-1.5 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6">
                    <form wire:submit.prevent="store">
                        <div class="space-y-4">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Jenis Aset') }}</label>
                                <select wire:model="type" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" required>
                                    <option value="crypto">{{ __('Kripto (Crypto)') }}</option>
                                    <option value="stock">{{ __('Saham (Stock)') }}</option>
                                    <option value="mutual_fund">{{ __('Reksadana') }}</option>
                                    <option value="gold">{{ __('Emas') }}</option>
                                    <option value="foreign_currency">{{ __('Valas (Mata Uang Asing)') }}</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Nama Aset') }}</label>
                                    <input type="text" wire:model="name" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" placeholder="{{ __('Cth: Bitcoin') }}" required>
                                    @error('name') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Simbol (Opsional)') }}</label>
                                    <input type="text" wire:model="symbol" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" placeholder="{{ __('Cth: BTC') }}">
                                </div>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Jumlah Kepemilikan (Qty)') }}</label>
                                <input type="number" step="any" wire:model="quantity" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" placeholder="{{ __('Cth: 0.05') }}" required>
                                @error('quantity') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Harga Beli Rata-Rata (Rp)') }}</label>
                                    <input type="number" wire:model="average_buy_price" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" placeholder="{{ __('Cth: 900000000') }}" min="0" required>
                                    @error('average_buy_price') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Harga Pasar Saat Ini (Rp)') }}</label>
                                    <input type="number" wire:model="current_price" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" placeholder="{{ __('Cth: 950000000') }}" min="0" required>
                                    @error('current_price') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Warna Kartu') }}</label>
                                <div class="flex gap-3 flex-wrap">
                                    @foreach($colors as $c)
                                        <label class="cursor-pointer">
                                            <input type="radio" wire:model="color" value="{{ $c }}" class="sr-only peer">
                                            <div class="w-8 h-8 rounded-full bg-{{ $c }}-500 peer-checked:ring-4 ring-offset-2 dark:ring-offset-slate-900 ring-{{ $c }}-500 transition-all"></div>
                                        </label>
                                    @endforeach
                                </div>
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

    <!-- Update Price Modal -->
    <div x-data="{ open: @entangle('isUpdatePriceModalOpen') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm" style="display: none;">
        <div class="relative w-full max-w-sm p-4">
            <div @click.away="open = false" class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-white/10 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">{{ __('Perbarui Harga Pasar') }}</h3>
                    <form wire:submit.prevent="updatePrice">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Harga Saat Ini (Rp)') }}</label>
                            <input type="number" wire:model="updateCurrentPrice" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" required>
                            @error('updateCurrentPrice') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex items-center justify-end space-x-3 mt-6">
                            <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors">
                                {{ __('Batal') }}
                            </button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-lg shadow-emerald-500/30 transition-colors">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
