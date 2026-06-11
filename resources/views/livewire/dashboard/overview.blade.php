<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Overview</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Ringkasan kondisi finansial Anda bulan ini.</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Balance -->
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl p-6 text-white shadow-lg shadow-indigo-500/30 relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-white opacity-10 blur-xl"></div>
            <p class="text-indigo-100 text-sm font-medium mb-1">Total Saldo Tersedia</p>
            <h2 class="text-3xl font-bold tracking-tight">Rp {{ number_format($totalBalance, 0, ',', '.') }}</h2>
            <div class="mt-4 flex items-center text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Terakumulasi dari semua dompet
            </div>
        </div>

        <!-- Income -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pemasukan Bulan Ini</p>
                <div class="p-2 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($incomeThisMonth, 0, ',', '.') }}</h2>
        </div>

        <!-- Expense -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pengeluaran Bulan Ini</p>
                <div class="p-2 bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($expenseThisMonth, 0, ',', '.') }}</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Main Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Tren Transaksi (7 Hari Terakhir)</h3>
            <div class="relative h-72">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Doughnut Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Distribusi Pengeluaran</h3>
            <div class="relative h-64 flex justify-center">
                @if(empty($categoryData))
                    <div class="flex items-center justify-center h-full text-sm text-gray-400">Belum ada pengeluaran</div>
                @else
                    <canvas id="categoryChart"></canvas>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Transaksi Terakhir</h3>
            <a href="{{ route('transactions.index') }}" wire:navigate class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 font-medium">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 uppercase">
                    <tr>
                        <th class="px-6 py-4 font-medium tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 font-medium tracking-wider">Kategori/Info</th>
                        <th class="px-6 py-4 font-medium tracking-wider text-right">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentTransactions as $trx)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300">
                            {{ $trx->transaction_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($trx->type === 'transfer')
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400 flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">Transfer</p>
                                        <p class="text-xs text-gray-500">{{ $trx->description }}</p>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3" style="background-color: {{ $trx->category?->color ?? '#9ca3af' }}20; color: {{ $trx->category?->color ?? '#9ca3af' }}">
                                        <span class="font-bold text-xs">{{ substr($trx->category?->icon ?? 'O', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $trx->category?->name ?? 'Tanpa Kategori' }}</p>
                                        <p class="text-xs text-gray-500">{{ $trx->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium {{ $trx->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : ($trx->type === 'expense' ? 'text-rose-600 dark:text-rose-400' : 'text-blue-600 dark:text-blue-400') }}">
                            {{ $trx->type === 'income' ? '+' : ($trx->type === 'expense' ? '-' : '') }}
                            {{ number_format($trx->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            Belum ada transaksi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart.js Init -->
    <script>
        document.addEventListener('livewire:navigated', function () {
            // Check if dark mode is active to adjust text colors
            const isDarkMode = document.documentElement.classList.contains('dark');
            const textColor = isDarkMode ? '#9ca3af' : '#4b5563';
            const gridColor = isDarkMode ? '#374151' : '#f3f4f6';

            // Trend Chart
            const trendCtx = document.getElementById('trendChart');
            if(trendCtx) {
                new Chart(trendCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($last7Days) !!},
                        datasets: [
                            {
                                label: 'Pemasukan',
                                data: {!! json_encode($incomeData) !!},
                                backgroundColor: '#10b981',
                                borderRadius: 4,
                            },
                            {
                                label: 'Pengeluaran',
                                data: {!! json_encode($expenseData) !!},
                                backgroundColor: '#f43f5e',
                                borderRadius: 4,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { labels: { color: textColor } }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: textColor } },
                            y: { grid: { color: gridColor }, ticks: { color: textColor } }
                        }
                    }
                });
            }

            // Category Chart
            const catCtx = document.getElementById('categoryChart');
            if(catCtx) {
                new Chart(catCtx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($categoryLabels) !!},
                        datasets: [{
                            data: {!! json_encode($categoryData) !!},
                            backgroundColor: {!! json_encode($categoryColors) !!},
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { color: textColor, padding: 20, usePointStyle: true }
                            }
                        },
                        cutout: '70%'
                    }
                });
            }
        });
    </script>
</div>
