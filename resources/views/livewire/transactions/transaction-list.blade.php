<div>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Transaksi</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Catat dan pantau seluruh transaksi Anda.</p>
        </div>
        <a href="{{ route('transactions.create') }}" wire:navigate class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/30 font-medium">
            + Transaksi Baru
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6 flex flex-wrap gap-4">
        <div class="w-full md:w-auto">
            <select wire:model.live="filterType" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Semua Tipe</option>
                <option value="income">Pemasukan</option>
                <option value="expense">Pengeluaran</option>
                <option value="transfer">Transfer</option>
            </select>
        </div>
        <div class="w-full md:w-auto">
            <select wire:model.live="filterWallet" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Semua Dompet</option>
                @foreach($wallets as $wallet)
                    <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-auto">
            <select wire:model.live="filterCategory" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    <!-- Transactions Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 uppercase">
                    <tr>
                        <th class="px-6 py-4 font-medium tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 font-medium tracking-wider">Kategori/Info</th>
                        <th class="px-6 py-4 font-medium tracking-wider">Dompet</th>
                        <th class="px-6 py-4 font-medium tracking-wider text-right">Jumlah (Rp)</th>
                        <th class="px-6 py-4 font-medium tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
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
                                        <p class="text-xs text-gray-500 truncate max-w-[200px]">{{ $trx->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300">
                            @if($trx->type === 'transfer')
                                {{ $trx->wallet->name }} &rarr; {{ $trx->toWallet?->name }}
                            @else
                                {{ $trx->wallet->name }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium {{ $trx->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : ($trx->type === 'expense' ? 'text-rose-600 dark:text-rose-400' : 'text-blue-600 dark:text-blue-400') }}">
                            {{ $trx->type === 'income' ? '+' : ($trx->type === 'expense' ? '-' : '') }}
                            {{ number_format($trx->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity flex justify-end gap-2">
                                <a href="{{ route('transactions.edit', $trx->id) }}" wire:navigate class="p-1 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <button wire:click="delete({{ $trx->id }})" wire:confirm="Yakin ingin menghapus transaksi ini?" class="p-1 text-gray-500 hover:text-rose-600 dark:hover:text-rose-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            Belum ada transaksi yang dicatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
