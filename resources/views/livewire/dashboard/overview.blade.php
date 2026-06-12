<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white tracking-tight">Dashboard Overview</h1>
        <p class="mt-1 text-sm text-slate-400">Ringkasan kondisi finansial Anda bulan ini.</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Balance - Premium Gradient Card -->
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 to-violet-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/40 hover:scale-[1.01] transition-all duration-300">
            <!-- Glow decorations -->
            <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -left-4 -bottom-4 w-24 h-24 rounded-full bg-violet-400/20 blur-xl"></div>
            <div class="absolute right-4 bottom-4 w-16 h-16 rounded-full bg-indigo-300/10 blur-lg"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-indigo-100 text-sm font-medium">Total Saldo Tersedia</p>
                    <div class="p-2 bg-white/10 rounded-xl backdrop-blur-sm">
                        <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <h2 class="text-3xl font-bold tracking-tight">Rp {{ number_format($totalBalance, 0, ',', '.') }}</h2>
                <div class="mt-4 flex items-center text-sm text-indigo-100/80">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Terakumulasi dari semua dompet
                </div>
            </div>
        </div>

        <!-- Income Card -->
        <div class="bg-slate-900/80 backdrop-blur-xl border border-white/[0.06] rounded-2xl p-6 shadow-lg shadow-emerald-500/5 hover:shadow-emerald-500/10 hover:scale-[1.01] transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-medium text-slate-400">Pemasukan Bulan Ini</p>
                <div class="p-2.5 bg-emerald-500/10 text-emerald-400 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-white">Rp {{ number_format($incomeThisMonth, 0, ',', '.') }}</h2>
            <div class="mt-3 h-1 w-full bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full" style="width: 100%"></div>
            </div>
        </div>

        <!-- Expense Card -->
        <div class="bg-slate-900/80 backdrop-blur-xl border border-white/[0.06] rounded-2xl p-6 shadow-lg shadow-rose-500/5 hover:shadow-rose-500/10 hover:scale-[1.01] transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-medium text-slate-400">Pengeluaran Bulan Ini</p>
                <div class="p-2.5 bg-rose-500/10 text-rose-400 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-white">Rp {{ number_format($expenseThisMonth, 0, ',', '.') }}</h2>
            <div class="mt-3 h-1 w-full bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-rose-500 to-rose-400 rounded-full" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Main Trend Chart -->
        <div class="lg:col-span-2 bg-slate-900/80 backdrop-blur-xl border border-white/[0.06] rounded-2xl shadow-lg shadow-indigo-500/5 p-6">
            <h3 class="text-lg font-semibold text-white mb-6">Tren Transaksi (7 Hari Terakhir)</h3>
            <div class="relative h-72">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Doughnut Chart -->
        <div class="bg-slate-900/80 backdrop-blur-xl border border-white/[0.06] rounded-2xl shadow-lg shadow-indigo-500/5 p-6">
            <h3 class="text-lg font-semibold text-white mb-6">Distribusi Pengeluaran</h3>
            <div class="relative h-64 flex justify-center">
                @if(empty($categoryData))
                    <div class="flex items-center justify-center h-full text-sm text-slate-500">Belum ada pengeluaran</div>
                @else
                    <canvas id="categoryChart"></canvas>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-slate-900/80 backdrop-blur-xl border border-white/[0.06] rounded-2xl shadow-lg shadow-indigo-500/5 overflow-hidden">
        <div class="p-6 border-b border-white/[0.06] flex justify-between items-center">
            <h3 class="text-lg font-semibold text-white">Transaksi Terakhir</h3>
            <a href="{{ route('transactions.index') }}" wire:navigate class="text-sm text-indigo-400 hover:text-indigo-300 font-medium transition-colors duration-200">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-400 bg-slate-800/50 uppercase">
                    <tr>
                        <th class="px-6 py-4 font-medium tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 font-medium tracking-wider">Kategori/Info</th>
                        <th class="px-6 py-4 font-medium tracking-wider text-right">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($recentTransactions as $trx)
                    <tr class="hover:bg-white/[0.02] transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-slate-300">
                            {{ $trx->transaction_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($trx->type === 'transfer')
                                    <div class="w-8 h-8 rounded-full bg-blue-500/15 text-blue-400 flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-white">Transfer</p>
                                        <p class="text-xs text-slate-500">{{ $trx->description }}</p>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3" style="background-color: {{ $trx->category?->color ?? '#9ca3af' }}20; color: {{ $trx->category?->color ?? '#9ca3af' }}">
                                        <span class="font-bold text-xs">{{ substr($trx->category?->icon ?? 'O', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-white">{{ $trx->category?->name ?? 'Tanpa Kategori' }}</p>
                                        <p class="text-xs text-slate-500">{{ $trx->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium {{ $trx->type === 'income' ? 'text-emerald-400' : ($trx->type === 'expense' ? 'text-rose-400' : 'text-blue-400') }}">
                            {{ $trx->type === 'income' ? '+' : ($trx->type === 'expense' ? '-' : '') }}
                            {{ number_format($trx->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-slate-500">
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
            const textColor = '#94a3b8';
            const gridColor = 'rgba(255,255,255,0.04)';

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
