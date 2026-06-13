<div>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('Transaksi') }}</h1>
            <p class="mt-1 text-sm text-slate-400">{{ __('Catat dan pantau seluruh transaksi Anda.') }}</p>
        </div>
        <a href="{{ route('transactions.create') }}" wire:navigate class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-lg shadow-indigo-500/25 transition-all duration-200 font-medium inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ __('Transaksi Baru') }}
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-slate-900/80 backdrop-blur-xl p-4 rounded-2xl border border-white/[0.06] mb-6 flex flex-wrap gap-4 shadow-lg shadow-indigo-500/5">
        <div class="w-full md:w-auto">
            <select wire:model.live="filterType" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white text-sm placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                <option value="">{{ __('Semua Tipe') }}</option>
                <option value="income">{{ __('Pemasukan') }}</option>
                <option value="expense">{{ __('Pengeluaran') }}</option>
                <option value="transfer">{{ __('Transfer') }}</option>
            </select>
        </div>
        <div class="w-full md:w-auto">
            <select wire:model.live="filterWallet" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white text-sm placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                <option value="">{{ __('Semua Dompet') }}</option>
                @foreach($wallets as $wallet)
                    <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-auto">
            <select wire:model.live="filterCategory" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white text-sm placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                <option value="">{{ __('Semua Kategori') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
            {{ session('message') }}
        </div>
    @endif

    <!-- Transactions Table -->
    <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-white/[0.06] overflow-hidden shadow-lg shadow-indigo-500/5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-400 bg-slate-800/50 uppercase">
                    <tr>
                        <th class="px-6 py-4 font-medium tracking-wider">{{ __('Tanggal') }}</th>
                        <th class="px-6 py-4 font-medium tracking-wider">{{ __('Kategori/Info') }}</th>
                        <th class="px-6 py-4 font-medium tracking-wider">{{ __('Dompet') }}</th>
                        <th class="px-6 py-4 font-medium tracking-wider text-right">{{ __('Jumlah (Rp)') }}</th>
                        <th class="px-6 py-4 font-medium tracking-wider text-right">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-slate-300">
                            {{ $trx->transaction_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($trx->type === 'transfer')
                                    <div class="w-8 h-8 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-white">{{ __('Transfer') }}</p>
                                        <p class="text-xs text-slate-500">{{ $trx->description }}</p>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3" style="background-color: {{ $trx->category?->color ?? '#9ca3af' }}20; color: {{ $trx->category?->color ?? '#9ca3af' }}">
                                        <span class="font-bold text-xs">{{ substr($trx->category?->icon ?? 'O', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-white">{{ $trx->category?->name ?? __('Tanpa Kategori') }}</p>
                                        <p class="text-xs text-slate-500 truncate max-w-[200px]">{{ $trx->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-300">
                            @if($trx->type === 'transfer')
                                {{ $trx->wallet->name }} &rarr; {{ $trx->toWallet?->name }}
                            @else
                                {{ $trx->wallet->name }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium {{ $trx->type === 'income' ? 'text-emerald-400' : ($trx->type === 'expense' ? 'text-rose-400' : 'text-blue-400') }}">
                            {{ $trx->type === 'income' ? '+' : ($trx->type === 'expense' ? '-' : '') }}
                            {{ number_format($trx->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex justify-end gap-2">
                                <a href="{{ route('transactions.edit', $trx->id) }}" wire:navigate class="p-1.5 rounded-lg text-slate-500 hover:text-indigo-400 hover:bg-indigo-500/10 transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <button wire:click="delete({{ $trx->id }})" wire:confirm="{{ __('Yakin ingin menghapus transaksi ini?') }}" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            {{ __('Belum ada transaksi yang dicatat.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-white/[0.06]">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
