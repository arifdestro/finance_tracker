<div>
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Anggaran (Budget)</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Atur batasan pengeluaran per kategori setiap bulannya.</p>
        </div>
        <div class="flex items-center gap-3">
            <select wire:model.live="month" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}">{{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                @endforeach
            </select>
            <select wire:model.live="year" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500">
                @foreach(range(date('Y')-1, date('Y')+1) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
            <button wire:click="create()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/30 font-medium whitespace-nowrap">
                + Tambah
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($budgetsData as $data)
            @php
                $isDanger = $data['raw_percentage'] >= 100;
                $isWarning = $data['raw_percentage'] >= 80 && !$isDanger;
                $barColor = $isDanger ? 'bg-rose-500' : ($isWarning ? 'bg-amber-500' : 'bg-emerald-500');
                $bgLight = $isDanger ? 'bg-rose-50 dark:bg-rose-500/10' : ($isWarning ? 'bg-amber-50 dark:bg-amber-500/10' : 'bg-white dark:bg-gray-800');
                $borderColor = $isDanger ? 'border-rose-200 dark:border-rose-800' : ($isWarning ? 'border-amber-200 dark:border-amber-800' : 'border-gray-200 dark:border-gray-700');
            @endphp
            
            <div class="rounded-2xl p-6 shadow-sm border {{ $borderColor }} {{ $bgLight }} relative overflow-hidden group">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: {{ $data['category']?->color ?? '#9ca3af' }}20; color: {{ $data['category']?->color ?? '#9ca3af' }}">
                            <span class="font-bold text-sm">{{ substr($data['category']?->icon ?? 'O', 0, 1) }}</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $data['category']?->name ?? 'Kategori Dihapus' }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($data['raw_percentage'], 1) }}% terpakai</p>
                        </div>
                    </div>
                    <div class="opacity-0 group-hover:opacity-100 transition-opacity flex gap-1">
                        <button wire:click="edit({{ $data['id'] }})" class="p-1 text-gray-400 hover:text-indigo-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <button wire:click="delete({{ $data['id'] }})" wire:confirm="Hapus anggaran ini?" class="p-1 text-gray-400 hover:text-rose-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="mb-2 flex justify-between text-sm">
                    <span class="font-medium text-gray-700 dark:text-gray-300">Rp {{ number_format($data['actual'], 0, ',', '.') }}</span>
                    <span class="text-gray-500">Rp {{ number_format($data['budgeted'], 0, ',', '.') }}</span>
                </div>

                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-2 overflow-hidden">
                    <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $data['percentage'] }}%"></div>
                </div>

                @if($isDanger)
                    <p class="text-xs text-rose-600 dark:text-rose-400 font-medium flex items-center gap-1 mt-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Anggaran melebihi batas!
                    </p>
                @elseif($isWarning)
                    <p class="text-xs text-amber-600 dark:text-amber-400 font-medium flex items-center gap-1 mt-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Anggaran hampir habis!
                    </p>
                @else
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-1 mt-3">
                        Sisa: Rp {{ number_format($data['budgeted'] - $data['actual'], 0, ',', '.') }}
                    </p>
                @endif
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-gray-800 rounded-2xl p-8 text-center shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Belum Ada Anggaran</h3>
                <p class="text-gray-500 text-sm">Buat anggaran pertama Anda untuk bulan ini untuk mulai memantau pengeluaran.</p>
            </div>
        @endforelse
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
                            {{ $budgetId ? 'Edit Anggaran' : 'Atur Anggaran Baru' }}
                        </h3>
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori Pengeluaran</label>
                                @if($budgetId)
                                    <!-- Edit mode: Show current category as text to prevent changing -->
                                    <input type="text" disabled value="{{ App\Models\Category::find($category_id)?->name }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                                @else
                                    <select wire:model="category_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($availableCategories as $category)
                                            <option value="{{ $category->id }}" {{ in_array($category->id, $existingCategoryIds) ? 'disabled' : '' }}>
                                                {{ $category->name }} {{ in_array($category->id, $existingCategoryIds) ? '(Sudah dianggarkan)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Batas Maksimal (Rp)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-500 font-medium">Rp</span>
                                    </div>
                                    <input type="number" wire:model="amount" class="w-full pl-12 py-3 font-semibold rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                @error('amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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
