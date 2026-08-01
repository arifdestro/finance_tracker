<div>
    {{-- Page Header --}}
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white tracking-tight">{{ __('Kategori Transaksi') }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Kelola kategori untuk pemasukan dan pengeluaran Anda.') }}</p>
        </div>
        <button wire:click="create()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-lg shadow-indigo-500/25 transition-all duration-200 font-medium text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ __('Tambah Kategori') }}
        </button>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-sm font-medium flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-600 dark:text-rose-400 text-sm font-medium flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Category Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($categories as $category)
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl p-5 border border-slate-200 dark:border-white/[0.06] hover:border-slate-300 dark:hover:border-white/[0.12] hover:shadow-lg hover:shadow-indigo-500/5 hover:scale-[1.01] transition-all duration-200 relative overflow-hidden group">
                {{-- Color accent bar --}}
                <div class="absolute top-0 left-0 w-1 h-full rounded-l-2xl" style="background-color: {{ $category->color }}"></div>

                <div class="flex items-center gap-4 pl-2">
                    {{-- Icon --}}
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 ring-1 ring-slate-200 dark:ring-white/[0.06]" style="background-color: {{ $category->color }}20; color: {{ $category->color }}">
                        <span class="font-bold text-lg">{{ substr($category->icon, 0, 1) }}</span>
                    </div>

                    {{-- Name & Type Badge --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-slate-800 dark:text-white truncate">{{ $category->name }}</h3>
                        <span class="inline-block mt-1 text-xs px-3 py-1 rounded-full font-medium {{ $category->type == 'income' ? 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-200 dark:ring-emerald-500/20' : 'bg-rose-100 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 ring-1 ring-rose-200 dark:ring-rose-500/20' }}">
                            {{ ucfirst($category->type) }}
                        </span>
                    </div>

                    {{-- Actions --}}
                    @if(!$category->is_default)
                        <div class="opacity-0 group-hover:opacity-100 transition-all duration-200 flex flex-col gap-1.5">
                            <button wire:click="edit({{ $category->id }})" class="p-1.5 rounded-lg text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-colors duration-150" title="{{ __('Edit') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button wire:click="delete({{ $category->id }})" class="p-1.5 rounded-lg text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors duration-150" title="{{ __('Hapus') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    @else
                        <div class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/5 text-xs text-slate-500 dark:text-slate-400 font-medium">{{ __('Default') }}</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Modal Form --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal Panel --}}
            <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl shadow-black/50 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-white/10">
                <form wire:submit.prevent="store">
                    <div class="px-6 pt-6 pb-5">
                        {{-- Modal Header --}}
                        <div class="mb-6">
                            <h3 class="text-lg leading-6 font-semibold text-slate-800 dark:text-white" id="modal-title">
                                {{ $categoryId ? __('Edit Kategori') : __('Tambah Kategori') }}
                            </h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                {{ $categoryId ? __('Perbarui informasi kategori Anda.') : __('Buat kategori baru untuk transaksi Anda.') }}
                            </p>
                        </div>

                        {{-- Form Fields --}}
                        <div class="space-y-5">
                            {{-- Nama Kategori --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Nama Kategori') }}</label>
                                <input type="text" wire:model="name" class="block w-full rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 shadow-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm px-4 py-2.5 transition-colors duration-150" placeholder="{{ __('Masukkan nama kategori...') }}">
                                @error('name') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Tipe --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Tipe') }}</label>
                                <select wire:model="type" class="block w-full rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-800 dark:text-white shadow-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm px-4 py-2.5 transition-colors duration-150">
                                    <option value="expense">{{ __('Pengeluaran (Expense)') }}</option>
                                    <option value="income">{{ __('Pemasukan (Income)') }}</option>
                                </select>
                                @error('type') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Warna & Ikon --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Warna') }}</label>
                                    <input type="color" wire:model="color" class="block w-full h-11 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 shadow-sm cursor-pointer transition-colors duration-150 p-1">
                                    @error('color') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Ikon') }}</label>
                                    <select wire:model="icon" class="block w-full rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-800 dark:text-white shadow-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm px-4 py-2.5 transition-colors duration-150">
                                        <option value="">{{ __('Pilih Ikon') }}</option>
                                        @foreach($icons as $ic)
                                            <option value="{{ $ic }}">{{ $ic }}</option>
                                        @endforeach
                                    </select>
                                    @error('icon') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 sm:flex sm:flex-row-reverse gap-3 border-t border-slate-200 dark:border-white/[0.06]">
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-xl px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-sm font-medium text-white shadow-lg shadow-indigo-500/25 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                            {{ __('Simpan') }}
                        </button>
                        <button type="button" wire:click="closeModal()" class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-xl px-5 py-2.5 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-sm font-medium text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/10 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                            {{ __('Batal') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
