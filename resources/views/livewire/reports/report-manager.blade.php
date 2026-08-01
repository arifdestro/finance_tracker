<div>
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white tracking-tight">{{ __('Laporan Keuangan') }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Analisis dan ekspor data transaksi Anda.') }}</p>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl p-2.5 rounded-2xl border border-slate-200 dark:border-white/[0.06]">
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-500 dark:text-slate-400 pl-2 font-medium">{{ __('Dari') }}</span>
                <input type="date" wire:model.live="startDate" class="rounded-xl bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-white/10 text-slate-700 dark:text-white text-sm focus:ring-indigo-500/50 focus:border-indigo-500 py-1.5 placeholder-slate-400 dark:placeholder-slate-500">
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-500 dark:text-slate-400 font-medium">{{ __('Sampai') }}</span>
                <input type="date" wire:model.live="endDate" class="rounded-xl bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-white/10 text-slate-700 dark:text-white text-sm focus:ring-indigo-500/50 focus:border-indigo-500 py-1.5 placeholder-slate-400 dark:placeholder-slate-500">
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl p-6 border border-slate-200 dark:border-white/[0.06] shadow-lg shadow-emerald-500/5 flex items-center gap-4 hover:scale-[1.01] transition-transform duration-200">
            <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center ring-1 ring-emerald-500/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Total Pemasukan') }}</p>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h2>
            </div>
        </div>
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl p-6 border border-slate-200 dark:border-white/[0.06] shadow-lg shadow-rose-500/5 flex items-center gap-4 hover:scale-[1.01] transition-transform duration-200">
            <div class="w-12 h-12 rounded-full bg-rose-100 dark:bg-rose-500/15 text-rose-600 dark:text-rose-400 flex items-center justify-center ring-1 ring-rose-500/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Total Pengeluaran') }}</p>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h2>
            </div>
        </div>
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl p-6 border border-amber-200 dark:border-amber-500/20 shadow-lg shadow-amber-500/5 flex items-center gap-4 hover:scale-[1.01] transition-transform duration-200 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent pointer-events-none"></div>
            <div class="relative w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-500/15 text-amber-600 dark:text-amber-400 flex items-center justify-center ring-1 ring-amber-500/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
            </div>
            <div class="relative">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Piutang Belum Lunas') }}</p>
                <h2 class="text-2xl font-bold text-amber-600 dark:text-amber-400">Rp {{ number_format($totalDebtUnpaid, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-3 mb-6">
        <button wire:click="exportCsv" wire:loading.attr="disabled" class="px-4 py-2.5 bg-white dark:bg-white/5 hover:bg-slate-50 dark:hover:bg-white/10 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-slate-300 transition-all duration-200 font-medium flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            {{ __('Export CSV') }}
        </button>
        <button wire:click="exportPdf" wire:loading.attr="disabled" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white border border-transparent rounded-xl transition-all duration-200 shadow-lg shadow-indigo-500/25 font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            {{ __('Export PDF') }}
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Category Summary -->
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-slate-200 dark:border-white/[0.06] p-6 shadow-lg shadow-indigo-500/5">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-5">{{ __('Pengeluaran Kategori') }}</h3>
            <div class="space-y-4">
                @forelse($expenseByCategory as $expense)
                    <div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="font-medium text-slate-600 dark:text-slate-300">{{ $expense->category?->name ?? __('Lain-lain') }}</span>
                            <span class="text-slate-800 dark:text-white font-semibold">Rp {{ number_format($expense->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                            <div class="h-1.5 rounded-full transition-all duration-500" style="background-color: {{ $expense->category?->color ?? '#9ca3af' }}; width: {{ $totalExpense > 0 ? ($expense->total / $totalExpense) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-10 h-10 text-slate-400 dark:text-slate-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <p class="text-sm text-slate-500">{{ __('Tidak ada data.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Transactions -->
        <div class="lg:col-span-2 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-slate-200 dark:border-white/[0.06] overflow-hidden shadow-lg shadow-indigo-500/5">
            <div class="p-6 border-b border-slate-200 dark:border-white/[0.06]">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">{{ __('Rincian Transaksi') }}</h3>
            </div>
            <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 uppercase sticky top-0 border-b border-slate-200 dark:border-transparent">
                        <tr>
                            <th class="px-6 py-4 font-medium tracking-wider">{{ __('Tanggal') }}</th>
                            <th class="px-6 py-4 font-medium tracking-wider">{{ __('Info') }}</th>
                            <th class="px-6 py-4 font-medium tracking-wider text-right">{{ __('Jumlah') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                        @forelse($transactions as $trx)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-slate-500 dark:text-slate-400">
                                {{ $trx->transaction_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-800 dark:text-white">{{ $trx->category?->name ?? ($trx->type == 'transfer' ? __('Transfer') : __('Lain-lain')) }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $trx->description }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-semibold {{ $trx->type === 'income' ? 'text-emerald-500 dark:text-emerald-400' : ($trx->type === 'expense' ? 'text-rose-500 dark:text-rose-400' : 'text-blue-500 dark:text-blue-400') }}">
                                {{ $trx->type === 'income' ? '+' : ($trx->type === 'expense' ? '-' : '') }}{{ number_format($trx->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <svg class="w-10 h-10 text-slate-400 dark:text-slate-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <p class="text-sm text-slate-500">{{ __('Tidak ada transaksi pada periode ini.') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
