<div>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Dompet & Rekening</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola sumber dana untuk transaksi Anda.</p>
        </div>
        <button wire:click="create()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/30 font-medium">
            + Tambah Dompet
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($wallets as $wallet)
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 dark:from-gray-800 dark:to-gray-950 rounded-2xl p-6 shadow-xl relative overflow-hidden group text-white">
                <!-- Decorative Circle -->
                <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full opacity-20 blur-2xl" style="background-color: {{ $wallet->color }}"></div>
                
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-lg">{{ $wallet->name }}</h3>
                            @if($wallet->is_default)
                                <span class="px-2 py-0.5 bg-yellow-500/20 text-yellow-300 text-xs rounded-full border border-yellow-500/30">Utama</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-400 uppercase tracking-wider mt-1">{{ $wallet->type }}</p>
                    </div>
                    <div class="opacity-0 group-hover:opacity-100 transition-opacity flex gap-2 z-10">
                        <button wire:click="edit({{ $wallet->id }})" class="p-1.5 bg-white/10 hover:bg-white/20 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <button wire:click="delete({{ $wallet->id }})" class="p-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
                
                <div class="mt-6">
                    <p class="text-sm text-gray-400 mb-1">Saldo</p>
                    <p class="text-3xl font-bold tracking-tight">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</p>
                </div>
            </div>
        @endforeach
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
                            {{ $walletId ? 'Edit Dompet' : 'Tambah Dompet Baru' }}
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Dompet / Rekening</label>
                                <input type="text" wire:model="name" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe</label>
                                    <select wire:model="type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="cash">Tunai (Cash)</option>
                                        <option value="bank">Rekening Bank</option>
                                        <option value="e-wallet">E-Wallet</option>
                                    </select>
                                    @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Warna Kartu</label>
                                    <input type="color" wire:model="color" class="w-full h-10 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 cursor-pointer p-1">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Saldo Awal</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" wire:model="balance" class="w-full pl-10 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                @error('balance') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex items-center mt-4">
                                <input id="is_default" type="checkbox" wire:model="is_default" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                                <label for="is_default" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    Jadikan sebagai dompet utama
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl border-t border-gray-200 dark:border-gray-700">
                        <button type="button" wire:click="closeModal()" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-white hover:bg-indigo-700 transition-colors">
                            Simpan Dompet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
