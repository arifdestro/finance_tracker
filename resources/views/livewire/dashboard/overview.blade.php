<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white tracking-tight">Dashboard Overview</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Ringkasan kondisi finansial Anda bulan ini.') }}</p>
    </div>

    <!-- Top Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Total Balance Card -->
        <div class="lg:col-span-2 bg-gradient-to-br from-indigo-600 to-violet-600 text-white rounded-2xl p-6 shadow-[0_0_40px_rgba(79,70,229,0.3)] relative overflow-hidden">
            <!-- Glow decorations -->
            <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -left-4 -bottom-4 w-24 h-24 rounded-full bg-violet-400/20 blur-xl"></div>
            <div class="absolute right-4 bottom-4 w-16 h-16 rounded-full bg-indigo-300/10 blur-lg"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-indigo-100 text-sm font-medium">{{ __('Total Saldo Tersedia') }}</p>
                    <div class="p-2 bg-white/10 rounded-xl backdrop-blur-sm">
                        <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <h2 class="text-3xl font-bold tracking-tight">Rp {{ number_format($totalBalance, 0, ',', '.') }}</h2>

                {{-- Total Seharusnya (Net Worth) --}}
                @if($totalDebt > 0 || $totalPayable > 0)
                <div class="mt-3 pt-3 border-t border-white/20 flex items-center justify-between">
                    <span class="text-xs text-indigo-200/70 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ ($totalBalance + $totalDebt - $totalPayable) >= $totalBalance ? 'bg-emerald-400' : 'bg-rose-400' }} inline-block"></span>
                        {{ __('Kekayaan Bersih') }}
                    </span>
                    <span class="text-sm font-bold {{ ($totalBalance + $totalDebt - $totalPayable) >= $totalBalance ? 'text-emerald-300' : 'text-rose-300' }}">Rp {{ number_format($totalBalance + $totalDebt - $totalPayable, 0, ',', '.') }}</span>
                </div>
                @else
                <div class="mt-4 flex items-center text-sm text-indigo-100/80">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ __('Total aset saat ini') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Income Card -->
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-white/[0.06] rounded-2xl p-6 shadow-lg shadow-emerald-500/5 hover:shadow-emerald-500/10 hover:scale-[1.01] transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Pemasukan Bulan Ini') }}</p>
                <div class="p-2.5 bg-emerald-500/10 text-emerald-500 dark:text-emerald-400 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Rp {{ number_format($incomeThisMonth, 0, ',', '.') }}</h2>
            <div class="mt-3 h-1 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full" style="width: 100%"></div>
            </div>
        </div>

        <!-- Expense Card -->
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-white/[0.06] rounded-2xl p-6 shadow-lg shadow-rose-500/5 hover:shadow-rose-500/10 hover:scale-[1.01] transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Pengeluaran Bulan Ini') }}</p>
                <div class="p-2.5 bg-rose-500/10 text-rose-500 dark:text-rose-400 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Rp {{ number_format($expenseThisMonth, 0, ',', '.') }}</h2>
            <div class="mt-3 h-1 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-rose-500 to-rose-400 rounded-full" style="width: 100%"></div>
            </div>
        </div>

        <!-- Debt Card (Piutang) -->
        <a href="{{ route('debts') }}" wire:navigate class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-amber-200 dark:border-amber-500/20 rounded-2xl p-6 shadow-lg shadow-amber-500/5 hover:shadow-amber-500/10 hover:scale-[1.01] transition-all duration-300 relative overflow-hidden block">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent pointer-events-none"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Total Piutang Aktif') }}</p>
                    <div class="p-2.5 bg-amber-500/10 text-amber-500 dark:text-amber-400 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-amber-500 dark:text-amber-400">Rp {{ number_format($totalDebt, 0, ',', '.') }}</h2>
                <div class="mt-3 h-1 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-amber-500 to-amber-400 rounded-full" style="width: 100%"></div>
                </div>
            </div>
        </a>

        <!-- Payable Card (Utang) -->
        <a href="{{ route('payables') }}" wire:navigate class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-rose-200 dark:border-rose-500/20 rounded-2xl p-6 shadow-lg shadow-rose-500/5 hover:shadow-rose-500/10 hover:scale-[1.01] transition-all duration-300 relative overflow-hidden block">
            <div class="absolute inset-0 bg-gradient-to-br from-rose-500/5 to-transparent pointer-events-none"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Total Utang Aktif') }}</p>
                    <div class="p-2.5 bg-rose-500/10 text-rose-500 dark:text-rose-400 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-rose-500 dark:text-rose-400">Rp {{ number_format($totalPayable, 0, ',', '.') }}</h2>
                <div class="mt-3 h-1 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-rose-500 to-rose-400 rounded-full" style="width: 100%"></div>
                </div>
            </div>
        </a>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Main Trend Chart -->
        <div class="lg:col-span-2 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-white/[0.06] rounded-2xl shadow-lg shadow-indigo-500/5 p-6">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-6">{{ __('Tren Transaksi (7 Hari Terakhir)') }}</h3>
            <div class="relative h-72">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Doughnut Chart -->
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-white/[0.06] rounded-2xl shadow-lg shadow-indigo-500/5 p-6">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-6">{{ __('Distribusi Pengeluaran') }}</h3>
            <div class="relative h-64 flex justify-center">
                @if(empty($categoryData))
                    <div class="flex items-center justify-center h-full text-sm text-slate-500 dark:text-slate-400">{{ __('Belum ada pengeluaran') }}</div>
                @else
                    <canvas id="categoryChart"></canvas>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Transactions -->
        <div class="lg:col-span-2 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-white/[0.06] rounded-2xl shadow-lg shadow-indigo-500/5 overflow-hidden">
            <div class="p-6 border-b border-slate-200 dark:border-white/[0.06] flex justify-between items-center">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">{{ __('Transaksi Terakhir') }}</h3>
                <a href="{{ route('transactions.index') }}" wire:navigate class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium transition-colors duration-200">{{ __('Lihat Semua') }}</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 uppercase">
                        <tr>
                            <th class="px-6 py-4 font-medium tracking-wider">{{ __('Tanggal') }}</th>
                            <th class="px-6 py-4 font-medium tracking-wider">{{ __('Kategori/Info') }}</th>
                            <th class="px-6 py-4 font-medium tracking-wider text-right">{{ __('Jumlah (Rp)') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                        @forelse($recentTransactions as $trx)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700 dark:text-slate-300">
                                {{ $trx->transaction_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($trx->type === 'transfer')
                                        <div class="w-8 h-8 rounded-full bg-blue-500/15 text-blue-600 dark:text-blue-400 flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800 dark:text-white">{{ __('Transfer') }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $trx->description }}</p>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3" style="background-color: {{ $trx->category?->color ?? '#9ca3af' }}20; color: {{ $trx->category?->color ?? '#9ca3af' }}">
                                            <span class="font-bold text-xs">{{ substr($trx->category?->icon ?? 'O', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800 dark:text-white">{{ $trx->category?->name ?? __('Tanpa Kategori') }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $trx->description }}</p>
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
                            <td colspan="3" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                {{ __('Belum ada transaksi.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Active Goals Widget -->
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-white/[0.06] rounded-2xl shadow-lg shadow-emerald-500/5 p-6 flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    {{ __('Target Tabungan') }}
                </h3>
                <a href="{{ route('goals') }}" wire:navigate class="text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium transition-colors duration-200">{{ __('Kelola') }}</a>
            </div>
            
            <div class="space-y-5 flex-1">
                @forelse($activeGoals as $goal)
                    <div>
                        <div class="flex justify-between items-end mb-1.5">
                            <p class="font-medium text-sm text-slate-800 dark:text-white truncate pr-2">{{ $goal->name }}</p>
                            <p class="text-xs font-bold text-{{ $goal->color }}-500">{{ $goal->progress_percentage }}%</p>
                        </div>
                        <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden mb-1">
                            <div class="h-full bg-gradient-to-r from-{{ $goal->color }}-500 to-{{ $goal->color }}-400 rounded-full" style="width: {{ $goal->progress_percentage }}%"></div>
                        </div>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 text-right">Rp {{ number_format($goal->current_amount, 0, ',', '.') }} / Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center opacity-70">
                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">{{ __('Belum ada target aktif') }}</p>
                        <a href="{{ route('goals') }}" wire:navigate class="px-4 py-2 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs font-medium rounded-lg transition-colors">{{ __('Buat Target') }}</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Chart.js Init -->
    <script>
        document.addEventListener('livewire:navigated', function () {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const gridColor = isDark ? 'rgba(255,255,255,0.04)' : 'rgba(0,0,0,0.04)';

            // Trend Chart
            const trendCtx = document.getElementById('trendChart');
            if(trendCtx) {
                let chartStatus = Chart.getChart(trendCtx);
                if (chartStatus) chartStatus.destroy();

                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($last7Days) !!},
                        datasets: [
                            {
                                label: '{{ __('Pemasukan') }}',
                                data: {!! json_encode($incomeData) !!},
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                            },
                            {
                                label: '{{ __('Pengeluaran') }}',
                                data: {!! json_encode($expenseData) !!},
                                borderColor: '#f43f5e',
                                backgroundColor: 'rgba(244, 63, 94, 0.1)',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                            },
                            {
                                label: '{{ __('Transfer') }}',
                                data: {!! json_encode($transferData) !!},
                                borderColor: '#6366f1',
                                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
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

            // Distribution Chart
            const distCtx = document.getElementById('categoryChart');
            if(distCtx) {
                let distStatus = Chart.getChart(distCtx);
                if (distStatus) distStatus.destroy();

                new Chart(distCtx, {
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
