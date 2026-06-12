<div>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Transaksi Berulang</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Otomatiskan pencatatan untuk tagihan atau pendapatan rutin Anda.</p>
        </div>
        <button wire:click="create()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/30 font-medium">
            + Tambah Baru
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 uppercase">
                    <tr>
                        <th class="px-6 py-4 font-medium tracking-wider">Info</th>
                        <th class="px-6 py-4 font-medium tracking-wider text-right">Jumlah</th>
                        <th class="px-6 py-4 font-medium tracking-wider">Siklus</th>
                        <th class="px-6 py-4 font-medium tracking-wider">Tgl Berikutnya</th>
                        <th class="px-6 py-4 font-medium tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 font-medium tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recurringTransactions as $rt)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $rt->description }}</p>
                            <p class="text-xs text-gray-500">
                                @if($rt->type === 'transfer')
                                    Transfer: {{ $rt->wallet->name }} &rarr; {{ $rt->toWallet?->name }}
                                @else
                                    {{ $rt->category?->name ?? 'Tanpa Kategori' }} ({{ $rt->wallet->name }})
                                @endif
                            </p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium {{ $rt->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : ($rt->type === 'expense' ? 'text-rose-600 dark:text-rose-400' : 'text-blue-600 dark:text-blue-400') }}">
                            {{ $rt->type === 'income' ? '+' : ($rt->type === 'expense' ? '-' : '') }}{{ number_format($rt->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300 capitalize">
                            {{ $rt->frequency }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap {{ $rt->next_date <= now() ? 'text-rose-600 font-medium' : 'text-gray-600 dark:text-gray-300' }}">
                            {{ $rt->next_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button wire:click="toggleActive({{ $rt->id }})" class="px-3 py-1 rounded-full text-xs font-medium {{ $rt->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' }}">
                                {{ $rt->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right flex justify-end gap-2">
                            <button wire:click="edit({{ $rt->id }})" class="p-1 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button wire:click="delete({{ $rt->id }})" wire:confirm="Hapus transaksi berulang ini?" class="p-1 text-gray-500 hover:text-rose-600 dark:hover:text-rose-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            Belum ada jadwal transaksi otomatis.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($recurringTransactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $recurringTransactions->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" wire:click="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700">
                <form wire:submit.prevent="store">
                    <div class="px-6 pt-6 pb-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
                            {{ $rtId ? 'Edit Transaksi Berulang' : 'Jadwalkan Transaksi Baru' }}
                        </h3>
                        
                        <div class="flex border-b border-gray-200 dark:border-gray-700 mb-6">
                            <button type="button" wire:click="$set('type', 'expense')" class="flex-1 py-2 text-center text-sm font-medium border-b-2 {{ $type === 'expense' ? 'border-rose-500 text-rose-600 dark:text-rose-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">Pengeluaran</button>
                            <button type="button" wire:click="$set('type', 'income')" class="flex-1 py-2 text-center text-sm font-medium border-b-2 {{ $type === 'income' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">Pemasukan</button>
                            <button type="button" wire:click="$set('type', 'transfer')" class="flex-1 py-2 text-center text-sm font-medium border-b-2 {{ $type === 'transfer' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">Transfer</button>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah</label>
                                <input type="number" wire:model="amount" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $type === 'transfer' ? 'Dari Dompet' : 'Dompet' }}</label>
                                    <select wire:model="wallet_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Pilih Dompet</option>
                                        @foreach($wallets as $wallet)
                                            <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('wallet_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                @if($type === 'transfer')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ke Dompet</label>
                                    <select wire:model="to_wallet_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Pilih Tujuan</option>
                                        @foreach($wallets as $wallet)
                                            <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('to_wallet_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                @else
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                                    <select wire:model="category_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi Tagihan/Pendapatan</label>
                                <input type="text" wire:model="description" placeholder="Contoh: Tagihan Listrik Bulanan" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Siklus</label>
                                    <select wire:model="frequency" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="daily">Harian</option>
                                        <option value="weekly">Mingguan</option>
                                        <option value="monthly">Bulanan</option>
                                        <option value="yearly">Tahunan</option>
                                    </select>
                                    @error('frequency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tgl Pemrosesan Berikutnya</label>
                                    <input type="date" wire:model="next_date" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('next_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="flex items-center mt-4">
                                <input id="is_active" type="checkbox" wire:model="is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                                <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    Status Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl border-t border-gray-200 dark:border-gray-700">
                        <button type="button" wire:click="closeModal()" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-white hover:bg-indigo-700 transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
