<div>
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Keuangan</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Analisis dan ekspor data transaksi Anda.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-3 bg-white dark:bg-gray-800 p-2 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500 dark:text-gray-400 pl-2">Dari</span>
                <input type="date" wire:model.live="startDate" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1.5">
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">Sampai</span>
                <input type="date" wire:model.live="endDate" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1.5">
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-emerald-100 dark:border-emerald-900/30 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pemasukan</p>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h2>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-rose-100 dark:border-rose-900/30 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengeluaran</p>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-3 mb-6">
        <button wire:click="exportCsv" wire:loading.attr="disabled" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export CSV
        </button>
        <button wire:click="exportPdf" wire:loading.attr="disabled" class="px-4 py-2 bg-indigo-600 text-white border border-transparent rounded-lg hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/30 font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export PDF
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Category Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pengeluaran Kategori</h3>
            <div class="space-y-4">
                @forelse($expenseByCategory as $expense)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ $expense->category?->name ?? 'Lain-lain' }}</span>
                            <span class="text-gray-900 dark:text-white">Rp {{ number_format($expense->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5 overflow-hidden">
                            <div class="h-1.5 rounded-full" style="background-color: {{ $expense->category?->color ?? '#9ca3af' }}; width: {{ $totalExpense > 0 ? ($expense->total / $totalExpense) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">Tidak ada data.</p>
                @endforelse
            </div>
        </div>

        <!-- Transactions -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Rincian Transaksi</h3>
            </div>
            <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 uppercase sticky top-0">
                        <tr>
                            <th class="px-6 py-4 font-medium tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 font-medium tracking-wider">Info</th>
                            <th class="px-6 py-4 font-medium tracking-wider text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($transactions as $trx)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300">
                                {{ $trx->transaction_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $trx->category?->name ?? ($trx->type == 'transfer' ? 'Transfer' : 'Lain-lain') }}</p>
                                <p class="text-xs text-gray-500">{{ $trx->description }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-medium {{ $trx->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : ($trx->type === 'expense' ? 'text-rose-600 dark:text-rose-400' : 'text-blue-600 dark:text-blue-400') }}">
                                {{ $trx->type === 'income' ? '+' : ($trx->type === 'expense' ? '-' : '') }}{{ number_format($trx->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada transaksi pada periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
