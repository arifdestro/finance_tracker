<div>
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('Anggaran (Budget)') }}</h1>
            <p class="mt-1 text-sm text-slate-400">{{ __('Atur batasan pengeluaran per kategori setiap bulannya.') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <select wire:model.live="month" class="rounded-xl border-white/10 bg-slate-800/50 text-white text-sm focus:ring-indigo-500/50 focus:border-indigo-500 placeholder-slate-500">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}">{{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                @endforeach
            </select>
            <select wire:model.live="year" class="rounded-xl border-white/10 bg-slate-800/50 text-white text-sm focus:ring-indigo-500/50 focus:border-indigo-500 placeholder-slate-500">
                @foreach(range(date('Y')-1, date('Y')+1) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
            <button wire:click="create()" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-500 transition-all duration-200 shadow-lg shadow-indigo-500/25 font-medium whitespace-nowrap">
                + {{ __('Tambah') }}
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 backdrop-blur-sm">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($budgetsData as $data)
            @php
                $isDanger = $data['raw_percentage'] >= 100;
                $isWarning = $data['raw_percentage'] >= 80 && !$isDanger;
                $barColor = $isDanger ? 'bg-rose-500' : ($isWarning ? 'bg-amber-500' : 'bg-emerald-500');
                $glowBorder = $isDanger ? 'border-rose-500/30 shadow-rose-500/10' : ($isWarning ? 'border-amber-500/30 shadow-amber-500/10' : 'border-white/[0.06] shadow-indigo-500/5');
                $barGlow = $isDanger ? 'shadow-rose-500/50' : ($isWarning ? 'shadow-amber-500/50' : 'shadow-emerald-500/50');
            @endphp

            <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl p-6 border {{ $glowBorder }} shadow-lg relative overflow-hidden group hover:scale-[1.01] transition-all duration-200">
                {{-- Subtle gradient overlay for status --}}
                @if($isDanger)
                    <div class="absolute inset-0 bg-gradient-to-br from-rose-500/5 to-transparent pointer-events-none"></div>
                @elseif($isWarning)
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent pointer-events-none"></div>
                @endif

                <div class="relative flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: {{ $data['category']?->color ?? '#9ca3af' }}20; color: {{ $data['category']?->color ?? '#9ca3af' }}">
                            <span class="font-bold text-sm">{{ substr($data['category']?->icon ?? 'O', 0, 1) }}</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-white">{{ $data['category']?->name ?? __('Kategori Dihapus') }}</h3>
                            <p class="text-xs text-slate-500">{{ number_format($data['raw_percentage'], 1) }}% {{ __('terpakai') }}</p>
                        </div>
                    </div>
                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex gap-1">
                        <button wire:click="edit({{ $data['id'] }})" class="p-1.5 text-slate-500 hover:text-indigo-400 rounded-lg hover:bg-white/5 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <button wire:click="delete({{ $data['id'] }})" wire:confirm="{{ __('Hapus anggaran ini?') }}" class="p-1.5 text-slate-500 hover:text-rose-400 rounded-lg hover:bg-white/5 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="relative mb-2 flex justify-between text-sm">
                    <span class="font-medium text-slate-300">Rp {{ number_format($data['actual'], 0, ',', '.') }}</span>
                    <span class="text-slate-500">Rp {{ number_format($data['budgeted'], 0, ',', '.') }}</span>
                </div>

                <div class="relative w-full bg-slate-800 rounded-full h-2.5 mb-2 overflow-hidden">
                    <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-500 shadow-md {{ $barGlow }}" style="width: {{ $data['percentage'] }}%"></div>
                </div>

                @if($isDanger)
                    <p class="relative text-xs text-rose-400 font-medium flex items-center gap-1 mt-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ __('Anggaran melebihi batas!') }}
                    </p>
                @elseif($isWarning)
                    <p class="relative text-xs text-amber-400 font-medium flex items-center gap-1 mt-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ __('Anggaran hampir habis!') }}
                    </p>
                @else
                    <p class="relative text-xs text-emerald-400 font-medium flex items-center gap-1 mt-3">
                        {{ __('Sisa:') }} Rp {{ number_format($data['budgeted'] - $data['actual'], 0, ',', '.') }}
                    </p>
                @endif
            </div>
        @empty
            <div class="col-span-full bg-slate-900/80 backdrop-blur-xl rounded-2xl p-8 text-center border border-white/[0.06] shadow-lg shadow-indigo-500/5">
                <div class="w-16 h-16 bg-indigo-500/10 text-indigo-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-white mb-1">{{ __('Belum Ada Anggaran') }}</h3>
                <p class="text-slate-500 text-sm">{{ __('Buat anggaran pertama Anda untuk bulan ini untuk mulai memantau pengeluaran.') }}</p>
            </div>
        @endforelse
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity" wire:click="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/10">
                <form wire:submit.prevent="store">
                    <div class="px-6 pt-6 pb-6">
                        <h3 class="text-xl font-semibold text-white mb-6">
                            {{ $budgetId ? __('Edit Anggaran') : __('Atur Anggaran Baru') }}
                        </h3>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-1">{{ __('Kategori Pengeluaran') }}</label>
                                @if($budgetId)
                                    <!-- Edit mode: Show current category as text to prevent changing -->
                                    <input type="text" disabled value="{{ App\Models\Category::find($category_id)?->name }}" class="w-full rounded-xl border-white/10 bg-slate-800/50 text-slate-500 cursor-not-allowed placeholder-slate-500">
                                @else
                                    <select wire:model="category_id" class="w-full rounded-xl border-white/10 bg-slate-800/50 text-white focus:ring-indigo-500/50 focus:border-indigo-500">
                                        <option value="">{{ __('Pilih Kategori') }}</option>
                                        @foreach($availableCategories as $category)
                                            <option value="{{ $category->id }}" {{ in_array($category->id, $existingCategoryIds) ? 'disabled' : '' }}>
                                                {{ $category->name }} {{ in_array($category->id, $existingCategoryIds) ? __('(Sudah dianggarkan)') : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-1">{{ __('Batas Maksimal (Rp)') }}</label>
                                <div class="relative"
                                     x-data="{ 
                                         displayValue: $wire.entangle('amount').live,
                                         formatRupiah(value) {
                                             if (!value) return '';
                                             let number_string = value.toString().replace(/[^,\d]/g, '').toString();
                                             let split = number_string.split(',');
                                             let sisa = split[0].length % 3;
                                             let rupiah = split[0].substr(0, sisa);
                                             let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
                                             if (ribuan) {
                                                 let separator = sisa ? '.' : '';
                                                 rupiah += separator + ribuan.join('.');
                                             }
                                             rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                                             return rupiah;
                                         },
                                         updateValue(event) {
                                             let raw = event.target.value.replace(/\./g, '');
                                             $wire.set('amount', raw);
                                             event.target.value = this.formatRupiah(raw);
                                         }
                                     }"
                                     x-init="$refs.input.value = formatRupiah(displayValue)">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-slate-500 font-medium">Rp</span>
                                    </div>
                                    <input type="text" x-ref="input" @input="updateValue" autocomplete="off" class="w-full pl-12 py-3 font-semibold rounded-xl border-white/10 bg-slate-800/50 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                                </div>
                                @error('amount') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-800/50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl border-t border-white/[0.06]">
                        <button type="button" wire:click="closeModal()" class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-slate-300 transition-all duration-200">
                            {{ __('Batal') }}
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 border border-transparent rounded-xl text-white transition-all duration-200 shadow-lg shadow-indigo-500/25">
                            {{ __('Simpan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
