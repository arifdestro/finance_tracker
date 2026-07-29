<div>
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('Piutang (Pinjaman)') }}</h1>
            <p class="mt-1 text-sm text-slate-400">{{ __('Catat uang yang Anda pinjamkan ke orang lain.') }}</p>
        </div>
        <button wire:click="create()" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-500 transition-all duration-200 shadow-lg shadow-indigo-500/25 font-medium whitespace-nowrap">
            + {{ __('Tambah Piutang') }}
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 backdrop-blur-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-amber-500/20 p-6 shadow-lg shadow-amber-500/5 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent pointer-events-none"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-slate-400">{{ __('Total Sisa Piutang Aktif') }}</p>
                    <p class="text-2xl font-bold text-amber-400">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-emerald-500/20 p-6 shadow-lg shadow-emerald-500/5 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent pointer-events-none"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-slate-400">{{ __('Total Sudah Dibayar') }}</p>
                    <p class="text-2xl font-bold text-emerald-400">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex gap-2 mb-6">
        <button wire:click="$set('filter', 'unpaid')" class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ $filter === 'unpaid' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-white/5 text-slate-400 border border-white/10 hover:bg-white/10' }}">
            {{ __('Belum Lunas') }}
        </button>
        <button wire:click="$set('filter', 'paid')" class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ $filter === 'paid' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-white/5 text-slate-400 border border-white/10 hover:bg-white/10' }}">
            {{ __('Sudah Lunas') }}
        </button>
        <button wire:click="$set('filter', 'all')" class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ $filter === 'all' ? 'bg-indigo-500/20 text-indigo-400 border border-indigo-500/30' : 'bg-white/5 text-slate-400 border border-white/10 hover:bg-white/10' }}">
            {{ __('Semua') }}
        </button>
    </div>

    <!-- Debt List -->
    <div class="space-y-4">
        @forelse($debts as $debt)
            @php
                $percent = $debt->amount > 0 ? min(100, round(($debt->paid_amount / $debt->amount) * 100)) : 0;
            @endphp
            <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl border {{ $debt->is_paid ? 'border-emerald-500/20' : ($debt->due_date && $debt->due_date->isPast() ? 'border-rose-500/30' : 'border-white/[0.06]') }} p-5 shadow-lg shadow-indigo-500/5 transition-all duration-200 relative overflow-hidden">
                @if($debt->is_paid)
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent pointer-events-none"></div>
                @elseif($debt->due_date && $debt->due_date->isPast())
                    <div class="absolute inset-0 bg-gradient-to-br from-rose-500/5 to-transparent pointer-events-none"></div>
                @endif

                <div class="relative flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex items-start gap-4 flex-1 min-w-0">
                        <div class="w-12 h-12 rounded-xl {{ $debt->is_paid ? 'bg-emerald-500/10' : 'bg-amber-500/10' }} flex items-center justify-center shrink-0 mt-1">
                            @if($debt->is_paid)
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="font-semibold text-white truncate">{{ $debt->borrower_name }}</h3>
                            <p class="text-sm text-slate-400 truncate">{{ $debt->description ?: __('Tanpa keterangan') }}</p>
                            
                            <!-- Progress Bar -->
                            <div class="mt-3 mb-2 max-w-md">
                                <div class="flex justify-between text-xs mb-1 font-medium">
                                    <span class="text-emerald-400">{{ __('Terbayar:') }} Rp {{ number_format($debt->paid_amount, 0, ',', '.') }}</span>
                                    <span class="{{ $debt->is_paid ? 'text-slate-500' : 'text-amber-400' }}">{{ __('Sisa:') }} Rp {{ number_format($debt->remaining_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="w-full bg-slate-800 rounded-full h-1.5 border border-white/5 overflow-hidden relative">
                                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-400 h-1.5 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                <span class="text-xs text-slate-500">{{ __('Sumber Asli:') }} {{ $debt->wallet->name }}</span>
                                <span class="text-xs text-slate-600">•</span>
                                <span class="text-xs text-slate-500">{{ $debt->debt_date->format('d M Y') }}</span>
                                @if($debt->due_date)
                                    <span class="text-xs text-slate-600">•</span>
                                    <span class="text-xs {{ $debt->due_date->isPast() && !$debt->is_paid ? 'text-rose-400 font-medium' : 'text-slate-500' }}">
                                        {{ __('Jatuh tempo:') }} {{ $debt->due_date->format('d M Y') }}
                                    </span>
                                @endif
                                @if($debt->is_paid && $debt->paid_date)
                                    <span class="text-xs text-slate-600">•</span>
                                    <span class="text-xs text-emerald-400">{{ __('Lunas:') }} {{ $debt->paid_date->format('d M Y') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-3 shrink-0">
                        <div class="text-right">
                            <span class="text-lg font-bold text-white block">
                                Rp {{ number_format($debt->amount, 0, ',', '.') }}
                            </span>
                            @if($debt->is_paid)
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 inline-block mt-1">{{ __('Lunas') }}</span>
                            @elseif($debt->paid_amount > 0)
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 inline-block mt-1">{{ __('Cicilan') }} ({{ $percent }}%)</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20 inline-block mt-1">{{ __('Belum Lunas') }}</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-1">
                            @if(!$debt->is_paid)
                                <button wire:click="openPaymentModal({{ $debt->id }})" class="p-2 rounded-lg hover:bg-indigo-500/10 text-slate-400 hover:text-indigo-400 transition-colors" title="{{ __('Bayar / Cicil') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                </button>
                                <button wire:click="markAsPaid({{ $debt->id }})" wire:confirm="{{ __('Tandai sisa piutang ini sebagai lunas penuh?') }}" class="p-2 rounded-lg hover:bg-emerald-500/10 text-slate-400 hover:text-emerald-400 transition-colors" title="{{ __('Lunas Penuh') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            @endif
                            <button wire:click="edit({{ $debt->id }})" class="p-2 rounded-lg hover:bg-white/10 text-slate-400 hover:text-white transition-colors" title="{{ __('Edit') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="delete({{ $debt->id }})" wire:confirm="{{ __('Hapus piutang ini? Semua riwayat cicilan akan ikut terhapus dan saldo dompet akan dikembalikan.') }}" class="p-2 rounded-lg hover:bg-rose-500/10 text-slate-400 hover:text-rose-400 transition-colors" title="{{ __('Hapus') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                @if($debt->payments->count() > 0)
                <div class="mt-4 pt-4 border-t border-white/5">
                    <p class="text-xs font-semibold text-slate-500 mb-2">{{ __('Riwayat Pembayaran:') }}</p>
                    <div class="space-y-2">
                        @foreach($debt->payments as $payment)
                        <div class="flex items-center justify-between text-sm bg-black/20 rounded-lg p-2 border border-white/5">
                            <div class="flex items-center gap-3">
                                <span class="text-slate-400 text-xs">{{ $payment->payment_date->format('d M Y') }}</span>
                                <span class="text-white">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                <span class="text-xs text-slate-500 hidden md:inline">→ {{ $payment->wallet->name }}</span>
                            </div>
                            <button wire:click="deletePayment({{ $payment->id }})" wire:confirm="{{ __('Batalkan pembayaran ini? Uang akan ditarik kembali dari dompet.') }}" class="text-rose-400 hover:text-rose-300 transition-colors text-xs p-1">
                                {{ __('Batal') }}
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        @empty
            <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-white/[0.06] p-12 text-center shadow-lg">
                <div class="w-16 h-16 rounded-2xl bg-amber-500/10 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-amber-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-slate-400 font-medium">{{ __('Belum ada piutang yang dicatat.') }}</p>
            </div>
        @endforelse
    </div>

    <!-- Edit/Create Debt Modal -->
    @if($isModalOpen)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:click.self="closeModal">
            <div class="bg-slate-900 rounded-2xl border border-white/10 w-full max-w-lg shadow-2xl shadow-black/50 overflow-hidden max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-white/[0.06]">
                    <h2 class="text-xl font-bold text-white">
                        {{ $debtId ? __('Edit Piutang') : __('Catat Piutang Baru') }}
                    </h2>
                    <p class="text-sm text-slate-400 mt-1">{{ $debtId ? __('Perbarui informasi piutang. Jumlah yang diubah tidak memengaruhi cicilan yang sudah ada.') : __('Catat uang yang Anda pinjamkan.') }}</p>
                </div>
                <form wire:submit="store" class="p-6 space-y-5">
                    <!-- Borrower Name -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">{{ __('Nama Peminjam') }}</label>
                        <input type="text" wire:model="borrower_name" placeholder="{{ __('Contoh: Ahmad, Budi...') }}" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                        @error('borrower_name') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Wallet & Amount -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">{{ __('Sumber Dana Asal') }}</label>
                            <select wire:model="wallet_id" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                                <option value="">{{ __('Pilih Dompet') }}</option>
                                @foreach($wallets as $wallet)
                                    <option value="{{ $wallet->id }}">{{ $wallet->name }} (Rp {{ number_format($wallet->balance, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                            @error('wallet_id') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">{{ __('Total Pinjaman') }}</label>
                            <div class="relative"
                                 x-data="{
                                     displayValue: $wire.entangle('amount').live,
                                     formatNumber(val) {
                                         let num = String(val).replace(/\D/g, '');
                                         return num.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                     }
                                 }">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">Rp</span>
                                <input type="text" inputmode="numeric"
                                       :value="formatNumber(displayValue)"
                                       @input="let raw = $event.target.value.replace(/\D/g, ''); displayValue = raw; $event.target.value = formatNumber(raw)"
                                       class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500 pl-10"
                                       placeholder="0">
                            </div>
                            @error('amount') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">{{ __('Tanggal Pinjam') }}</label>
                            <input type="date" wire:model="debt_date" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                            @error('debt_date') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">{{ __('Jatuh Tempo (Opsional)') }}</label>
                            <input type="date" wire:model="due_date" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                            @error('due_date') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">{{ __('Keterangan (Opsional)') }}</label>
                        <input type="text" wire:model="description" placeholder="{{ __('Contoh: Pinjaman untuk modal usaha') }}" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">{{ __('Catatan Tambahan (Opsional)') }}</label>
                        <textarea wire:model="notes" rows="2" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="closeModal" class="px-5 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 rounded-xl transition-all duration-200 font-medium">
                            {{ __('Batal') }}
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-lg shadow-indigo-500/25 transition-all duration-200 font-medium">
                            {{ __('Simpan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Payment Modal -->
    @if($isPaymentModalOpen && $debtForPayment)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:click.self="closePaymentModal">
            <div class="bg-slate-900 rounded-2xl border border-white/10 w-full max-w-lg shadow-2xl shadow-black/50 overflow-hidden max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-white/[0.06]">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        {{ __('Tambah Cicilan / Pembayaran') }}
                    </h2>
                    <p class="text-sm text-slate-400 mt-1">{{ __('Catat pembayaran dari ') }} <span class="text-white font-medium">{{ $debtForPayment->borrower_name }}</span></p>
                </div>
                
                <div class="p-6 bg-slate-800/30 border-b border-white/5">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-slate-400">{{ __('Total Pinjaman:') }}</span>
                        <span class="font-medium text-white">Rp {{ number_format($debtForPayment->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-slate-400">{{ __('Sudah Dibayar:') }}</span>
                        <span class="font-medium text-emerald-400">Rp {{ number_format($debtForPayment->paid_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400">{{ __('Sisa Tagihan:') }}</span>
                        <span class="font-bold text-amber-400">Rp {{ number_format($debtForPayment->remaining_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form wire:submit="storePayment" class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Wallet -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">{{ __('Masuk Ke Dompet') }}</label>
                            <select wire:model="payment_wallet_id" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                                <option value="">{{ __('Pilih Dompet') }}</option>
                                @foreach($wallets as $wallet)
                                    <option value="{{ $wallet->id }}">{{ $wallet->name }} (Rp {{ number_format($wallet->balance, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                            @error('payment_wallet_id') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Amount -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">{{ __('Jumlah Bayar') }}</label>
                            <div class="relative"
                                 x-data="{
                                     displayValue: $wire.entangle('payment_amount').live,
                                     formatNumber(val) {
                                         let num = String(val).replace(/\D/g, '');
                                         return num.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                     }
                                 }">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">Rp</span>
                                <input type="text" inputmode="numeric"
                                       :value="formatNumber(displayValue)"
                                       @input="let raw = $event.target.value.replace(/\D/g, ''); displayValue = raw; $event.target.value = formatNumber(raw)"
                                       class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500 pl-10"
                                       placeholder="0">
                            </div>
                            @error('payment_amount') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">{{ __('Tanggal Pembayaran') }}</label>
                        <input type="date" wire:model="payment_date" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                        @error('payment_date') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">{{ __('Catatan (Opsional)') }}</label>
                        <input type="text" wire:model="payment_notes" placeholder="{{ __('Contoh: Cicilan pertama') }}" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="closePaymentModal" class="px-5 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 rounded-xl transition-all duration-200 font-medium">
                            {{ __('Batal') }}
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl shadow-lg shadow-emerald-500/25 transition-all duration-200 font-medium">
                            {{ __('Simpan Pembayaran') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
