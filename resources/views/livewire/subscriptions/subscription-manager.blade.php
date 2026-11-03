<div>
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">{{ __('Manajer Langganan') }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Pantau layanan berlangganan dan ketahui total uang hangus (Burn Rate) Anda.') }}</p>
        </div>
        <button wire:click="openModal" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ __('Tambah Langganan') }}
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    <!-- Burn Rate Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-gradient-to-br from-indigo-600 to-violet-600 rounded-3xl p-6 text-white shadow-xl shadow-indigo-500/20 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 font-medium mb-1">{{ __('Monthly Burn Rate') }}</p>
                    <h2 class="text-3xl font-bold">Rp {{ number_format($monthlyBurnRate, 0, ',', '.') }}</h2>
                    <p class="text-xs text-indigo-200 mt-2">{{ __('Uang yang pasti keluar setiap bulan') }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </div>
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-white/[0.06] rounded-3xl p-6 shadow-lg relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 dark:text-slate-400 font-medium mb-1">{{ __('Yearly Burn Rate') }}</p>
                    <h2 class="text-3xl font-bold text-slate-800 dark:text-white">Rp {{ number_format($yearlyBurnRate, 0, ',', '.') }}</h2>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">{{ __('Total biaya berlangganan per tahun') }}</p>
                </div>
                <div class="w-12 h-12 bg-rose-500/10 rounded-2xl flex items-center justify-center text-rose-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscriptions Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($subscriptions as $sub)
            @php
                $daysUntilDue = $sub->days_until_due;
                $isDueSoon = $daysUntilDue >= 0 && $daysUntilDue <= 3;
                $isOverdue = $daysUntilDue < 0;
            @endphp
            
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border {{ $isDueSoon ? 'border-amber-400 dark:border-amber-500/50 shadow-amber-500/20 shadow-lg' : ($isOverdue ? 'border-rose-400 dark:border-rose-500/50 shadow-rose-500/20 shadow-lg' : 'border-slate-200 dark:border-white/[0.06]') }} rounded-3xl p-6 transition-all duration-300 relative group">
                
                @if($isDueSoon || $isOverdue)
                    <div class="absolute top-0 right-0 -mt-2 -mr-2">
                        <span class="flex h-4 w-4 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $isOverdue ? 'bg-rose-400' : 'bg-amber-400' }} opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 {{ $isOverdue ? 'bg-rose-500' : 'bg-amber-500' }}"></span>
                        </span>
                    </div>
                @endif

                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-{{ $sub->color }}-500/10 text-{{ $sub->color }}-500 flex items-center justify-center flex-shrink-0 shadow-inner">
                            <span class="text-2xl font-bold">{{ substr($sub->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-slate-800 dark:text-white leading-tight">{{ $sub->name }}</h3>
                            <a href="{{ $sub->url ?: '#' }}" target="_blank" class="text-xs text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 transition-colors {{ !$sub->url ? 'invisible' : '' }}">{{ __('Kelola Layanan') }} &nearr;</a>
                        </div>
                    </div>
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-40 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-white/10 overflow-hidden z-20" style="display: none;">
                            <button wire:click="edit({{ $sub->id }})" class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">{{ __('Edit') }}</button>
                            <button wire:click="delete({{ $sub->id }})" class="w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors border-t border-slate-100 dark:border-white/5">{{ __('Hapus') }}</button>
                        </div>
                    </div>
                </div>

                <div class="mb-5 bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-100 dark:border-white/5">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">{{ __('Biaya ') . ($sub->cycle === 'monthly' ? __('Per Bulan') : __('Per Tahun')) }}</p>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">Rp {{ number_format($sub->price, 0, ',', '.') }}</p>
                </div>

                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">{{ __('Jatuh Tempo Berikutnya') }}</p>
                        <p class="text-sm font-semibold {{ $isDueSoon ? 'text-amber-600 dark:text-amber-400' : ($isOverdue ? 'text-rose-600 dark:text-rose-400' : 'text-slate-700 dark:text-slate-300') }}">
                            {{ $sub->next_due_date->format('d M Y') }} 
                            @if($isOverdue)
                                ({{ __('Terlewat') }})
                            @elseif($daysUntilDue === 0)
                                ({{ __('Hari ini') }})
                            @elseif($daysUntilDue <= 3)
                                ({{ $daysUntilDue }} {{ __('hari lagi') }})
                            @endif
                        </p>
                    </div>
                    <button wire:click="markAsPaid({{ $sub->id }})" title="{{ __('Tandai sudah bayar (Maju ke periode berikutnya)') }}" class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-all duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 px-4 text-center bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm border border-dashed border-slate-300 dark:border-slate-700 rounded-3xl">
                <div class="w-20 h-20 mx-auto bg-indigo-50 dark:bg-indigo-500/10 rounded-full flex items-center justify-center mb-4 text-indigo-500">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">{{ __('Belum ada langganan tercatat') }}</h3>
                <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-6">{{ __('Catat layanan langganan Anda (Netflix, Spotify, Internet) untuk melacak pengeluaran pasti Anda setiap bulannya.') }}</p>
                <button wire:click="openModal" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200">
                    {{ __('Tambah Langganan') }}
                </button>
            </div>
        @endforelse
    </div>

    <!-- Create/Edit Modal -->
    <div x-data="{ open: @entangle('isModalOpen') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/50 backdrop-blur-sm" style="display: none;">
        <div class="relative w-full max-w-md p-4 h-full md:h-auto flex items-center justify-center">
            <div @click.away="open = false" class="relative bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-white/10 w-full overflow-hidden">
                <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-white/10">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">
                        {{ $subscriptionId ? __('Edit Langganan') : __('Tambah Langganan') }}
                    </h3>
                    <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg p-1.5 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6">
                    <form wire:submit.prevent="store">
                        <div class="space-y-5">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Nama Layanan') }}</label>
                                <input type="text" wire:model="name" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" placeholder="{{ __('Cth: Netflix Premium') }}" required>
                                @error('name') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Biaya (Rp)') }}</label>
                                    <input type="number" wire:model="price" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" placeholder="186000" min="0" required>
                                    @error('price') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Siklus') }}</label>
                                    <select wire:model="cycle" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" required>
                                        <option value="monthly">{{ __('Per Bulan') }}</option>
                                        <option value="yearly">{{ __('Per Tahun') }}</option>
                                    </select>
                                    @error('cycle') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Jatuh Tempo Berikutnya') }}</label>
                                <input type="date" wire:model="next_due_date" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" style="color-scheme: dark;" required>
                                @error('next_due_date') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Warna Penanda') }}</label>
                                <div class="flex gap-3 flex-wrap">
                                    @foreach($colors as $c)
                                        <label class="cursor-pointer">
                                            <input type="radio" wire:model="color" value="{{ $c }}" class="sr-only peer">
                                            <div class="w-8 h-8 rounded-full bg-{{ $c }}-500 peer-checked:ring-4 ring-offset-2 dark:ring-offset-slate-900 ring-{{ $c }}-500 transition-all"></div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Tautan Pengaturan (Opsional)') }}</label>
                                <input type="url" wire:model="url" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" placeholder="https://netflix.com/account">
                                @error('url') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-slate-200 dark:border-white/10">
                            <button type="button" @click="open = false" class="px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors">
                                {{ __('Batal') }}
                            </button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-500/30 transition-colors">
                                {{ __('Simpan') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
