<div>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ $transactionId ? 'Edit Transaksi' : 'Transaksi Baru' }}</h1>
        </div>
        <a href="{{ route('transactions.index') }}" wire:navigate class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-slate-300 transition-all duration-200 font-medium">
            Kembali
        </a>
    </div>

    <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-white/[0.06] max-w-3xl mx-auto overflow-hidden shadow-lg shadow-indigo-500/5">

        <!-- Type Selector -->
        <div class="flex border-b border-white/[0.06]">
            <button wire:click="$set('type', 'expense')" class="flex-1 py-4 text-center font-medium transition-all duration-200 border-b-2 {{ $type === 'expense' ? 'border-rose-500 text-rose-400 bg-rose-500/10' : 'border-transparent text-slate-400 hover:text-slate-300 hover:bg-white/[0.02]' }}">
                Pengeluaran
            </button>
            <button wire:click="$set('type', 'income')" class="flex-1 py-4 text-center font-medium transition-all duration-200 border-b-2 {{ $type === 'income' ? 'border-emerald-500 text-emerald-400 bg-emerald-500/10' : 'border-transparent text-slate-400 hover:text-slate-300 hover:bg-white/[0.02]' }}">
                Pemasukan
            </button>
            <button wire:click="$set('type', 'transfer')" class="flex-1 py-4 text-center font-medium transition-all duration-200 border-b-2 {{ $type === 'transfer' ? 'border-blue-500 text-blue-400 bg-blue-500/10' : 'border-transparent text-slate-400 hover:text-slate-300 hover:bg-white/[0.02]' }}">
                Transfer
            </button>
        </div>

        <form wire:submit.prevent="save" class="p-6 sm:p-8 space-y-6">

            <!-- Amount -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Jumlah</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-slate-500 font-medium">Rp</span>
                    </div>
                    <input type="number" wire:model="amount" class="w-full pl-12 py-3 text-lg font-semibold rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                </div>
                @error('amount') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Wallets -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">
                        {{ $type === 'transfer' ? 'Dari Dompet' : 'Dompet' }}
                    </label>
                    <select wire:model="wallet_id" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                        <option value="">Pilih Dompet</option>
                        @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}">{{ $wallet->name }} (Rp {{ number_format($wallet->balance, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                    @error('wallet_id') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
                </div>

                @if($type === 'transfer')
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Ke Dompet</label>
                    <select wire:model="to_wallet_id" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                        <option value="">Pilih Tujuan</option>
                        @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                        @endforeach
                    </select>
                    @error('to_wallet_id') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
                </div>
                @else
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Kategori</label>
                    <select wire:model="category_id" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>

            <!-- Date & Description -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Tanggal Transaksi</label>
                    <input type="date" wire:model="transaction_date" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                    @error('transaction_date') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Judul / Deskripsi Singkat</label>
                    <input type="text" wire:model="description" placeholder="Contoh: Makan siang" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500">
                    @error('description') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Catatan Tambahan (Opsional)</label>
                <textarea wire:model="notes" rows="3" class="w-full rounded-xl bg-slate-800/50 border-white/10 text-white placeholder-slate-500 focus:ring-indigo-500/50 focus:border-indigo-500"></textarea>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl shadow-lg shadow-indigo-500/25 transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-indigo-500">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
