<div>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kategori Transaksi</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola kategori untuk pemasukan dan pengeluaran Anda.</p>
        </div>
        <button wire:click="create()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/30 font-medium">
            + Tambah Kategori
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($categories as $category)
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1 h-full" style="background-color: {{ $category->color }}"></div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-opacity-20" style="background-color: {{ $category->color }}30; color: {{ $category->color }}">
                        <!-- Simple Icon representation -->
                        <span class="font-bold text-lg">{{ substr($category->icon, 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $category->name }}</h3>
                        <span class="text-xs px-2 py-1 rounded-full {{ $category->type == 'income' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' }}">
                            {{ ucfirst($category->type) }}
                        </span>
                    </div>
                    
                    @if(!$category->is_default)
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity flex flex-col gap-1">
                            <button wire:click="edit({{ $category->id }})" class="p-1 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button wire:click="delete({{ $category->id }})" class="p-1 text-gray-500 hover:text-rose-600 dark:hover:text-rose-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    @else
                        <div class="text-xs text-gray-400 dark:text-gray-500">Default</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700">
                <form wire:submit.prevent="store">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                {{ $categoryId ? 'Edit Kategori' : 'Tambah Kategori' }}
                            </h3>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kategori</label>
                                <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe</label>
                                <select wire:model="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="expense">Pengeluaran (Expense)</option>
                                    <option value="income">Pemasukan (Income)</option>
                                </select>
                                @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Warna</label>
                                    <input type="color" wire:model="color" class="mt-1 block w-full h-10 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm cursor-pointer">
                                    @error('color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ikon</label>
                                    <select wire:model="icon" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Pilih Ikon</option>
                                        @foreach($icons as $ic)
                                            <option value="{{ $ic }}">{{ $ic }}</option>
                                        @endforeach
                                    </select>
                                    @error('icon') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan
                        </button>
                        <button type="button" wire:click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
