<div>
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('transactions.index') }}" wire:navigate class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:text-slate-800 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">{{ __('Impor Mutasi CSV') }}</h1>
        </div>
        <p class="text-sm text-slate-500 dark:text-slate-400 ml-13">{{ __('Impor riwayat transaksi dari bank Anda sekaligus. Mendukung format CSV dari berbagai bank.') }}</p>
    </div>

    @if($importError)
        <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-600 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ $importError }}
        </div>
    @endif

    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-white/[0.06] rounded-3xl p-6 md:p-10 shadow-xl shadow-indigo-500/5">
        
        <!-- STEP INDICATOR -->
        <div class="flex items-center justify-center mb-10">
            <div class="flex items-center gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm {{ $step >= 1 ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }} transition-colors">1</div>
                    <span class="text-xs font-medium mt-2 {{ $step >= 1 ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}">{{ __('Upload') }}</span>
                </div>
                <div class="w-16 h-1 rounded-full {{ $step >= 2 ? 'bg-indigo-600' : 'bg-slate-100 dark:bg-slate-800' }} transition-colors"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm {{ $step >= 2 ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }} transition-colors">2</div>
                    <span class="text-xs font-medium mt-2 {{ $step >= 2 ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}">{{ __('Pemetaan') }}</span>
                </div>
                <div class="w-16 h-1 rounded-full {{ $step >= 3 ? 'bg-indigo-600' : 'bg-slate-100 dark:bg-slate-800' }} transition-colors"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm {{ $step >= 3 ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }} transition-colors">3</div>
                    <span class="text-xs font-medium mt-2 {{ $step >= 3 ? 'text-emerald-500' : 'text-slate-400' }}">{{ __('Selesai') }}</span>
                </div>
            </div>
        </div>

        @if($step === 1)
            <!-- STEP 1: UPLOAD -->
            <form wire:submit.prevent="processFile" class="max-w-2xl mx-auto space-y-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Pilih Dompet Tujuan') }}</label>
                    <div class="relative">
                        <select wire:model="wallet_id" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-4 appearance-none transition-colors" required>
                            @foreach($wallets as $wallet)
                                <option value="{{ $wallet->id }}">{{ $wallet->name }} (Rp {{ number_format($wallet->balance, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('File CSV Mutasi') }}</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-slate-300 dark:border-slate-600 border-dashed rounded-2xl cursor-pointer bg-slate-50 dark:bg-slate-800/30 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-slate-500 dark:text-slate-400">
                                <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="mb-2 text-sm font-semibold"><span class="text-indigo-600 dark:text-indigo-400">{{ __('Klik untuk memilih file') }}</span> {{ __('atau drag and drop') }}</p>
                                <p class="text-xs">CSV (MAX. 5MB)</p>
                            </div>
                            <input id="dropzone-file" type="file" wire:model="file" accept=".csv" class="hidden" required />
                        </label>
                    </div>
                    
                    <div wire:loading wire:target="file" class="mt-2 text-sm text-indigo-600 font-medium">
                        {{ __('Mengunggah file...') }}
                    </div>
                    @if($file)
                        <div class="mt-3 p-3 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 rounded-xl text-sm font-medium flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            File terpilih: {{ $file->getClientOriginalName() }}
                        </div>
                    @endif
                    @error('file') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200" wire:loading.attr="disabled" wire:target="processFile">
                        <span wire:loading.remove wire:target="processFile">{{ __('Lanjutkan ke Pemetaan') }} &rarr;</span>
                        <span wire:loading wire:target="processFile">{{ __('Memproses...') }}</span>
                    </button>
                </div>
            </form>

        @elseif($step === 2)
            <!-- STEP 2: MAPPING -->
            <div class="space-y-8">
                <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-2xl p-4 text-blue-800 dark:text-blue-300 text-sm">
                    <strong>{{ __('Petunjuk:') }}</strong> Kami telah membaca file CSV Anda. Silakan cocokkan (mapping) kolom mana yang berisi <strong>Tanggal</strong>, <strong>Deskripsi</strong>, dan <strong>Nominal</strong> agar sistem dapat mengimpornya dengan benar.
                </div>

                <form wire:submit.prevent="performImport">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-200 dark:border-white/5">
                        
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-800 dark:text-white">{{ __('Kolom Tanggal') }} <span class="text-rose-500">*</span></label>
                            <select wire:model="mapDate" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-2.5" required>
                                <option value="">-- Pilih Kolom --</option>
                                @foreach($csvHeaders as $index => $header)
                                    <option value="{{ $index }}">{{ $header }} (Kolom {{ $index + 1 }})</option>
                                @endforeach
                            </select>
                            @error('mapDate') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-800 dark:text-white">{{ __('Kolom Keterangan') }} <span class="text-rose-500">*</span></label>
                            <select wire:model="mapDescription" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-2.5" required>
                                <option value="">-- Pilih Kolom --</option>
                                @foreach($csvHeaders as $index => $header)
                                    <option value="{{ $index }}">{{ $header }} (Kolom {{ $index + 1 }})</option>
                                @endforeach
                            </select>
                            @error('mapDescription') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-800 dark:text-white">{{ __('Kolom Nominal (Uang)') }} <span class="text-rose-500">*</span></label>
                            <select wire:model="mapAmount" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-2.5" required>
                                <option value="">-- Pilih Kolom --</option>
                                @foreach($csvHeaders as $index => $header)
                                    <option value="{{ $index }}">{{ $header }} (Kolom {{ $index + 1 }})</option>
                                @endforeach
                            </select>
                            @error('mapAmount') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-800 dark:text-white">{{ __('Kolom Tipe (DB/CR)') }} <span class="text-slate-400 font-normal">(Opsional)</span></label>
                            <select wire:model="mapType" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-2.5">
                                <option value="">-- Otomatis (Dari Nominal) --</option>
                                @foreach($csvHeaders as $index => $header)
                                    <option value="{{ $index }}">{{ $header }} (Kolom {{ $index + 1 }})</option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-slate-500 mt-1">Biarkan otomatis jika nominal sudah memiliki tanda minus (-) untuk pengeluaran.</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-3">{{ __('Pratinjau Data (3 Baris Pertama):') }}</h4>
                        <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-white/10">
                            <table class="w-full text-sm text-left whitespace-nowrap">
                                <thead class="text-xs text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800/80 uppercase">
                                    <tr>
                                        @foreach($csvHeaders as $index => $header)
                                            <th class="px-4 py-3 border-b border-slate-200 dark:border-white/5">
                                                <span class="block text-[10px] opacity-70">Kolom {{ $index + 1 }}</span>
                                                {{ $header }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-white/5 bg-white dark:bg-slate-900">
                                    @foreach($csvPreview as $row)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                            @foreach($row as $cell)
                                                <td class="px-4 py-3 text-slate-700 dark:text-slate-300 truncate max-w-[200px]">{{ $cell }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-10 flex justify-between items-center pt-6 border-t border-slate-200 dark:border-white/10">
                        <button type="button" wire:click="resetImporter" class="px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors">
                            &larr; {{ __('Kembali & Ganti File') }}
                        </button>
                        <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all duration-200" wire:loading.attr="disabled" wire:target="performImport">
                            <span wire:loading.remove wire:target="performImport">{{ __('Jalankan Impor') }}</span>
                            <span wire:loading wire:target="performImport" class="flex items-center gap-2">
                                <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                {{ __('Menyimpan Data...') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>

        @elseif($step === 3)
            <!-- STEP 3: SUCCESS -->
            <div class="text-center py-10">
                <div class="w-24 h-24 rounded-full bg-emerald-500/10 text-emerald-500 mx-auto flex items-center justify-center mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 class="text-3xl font-bold text-slate-800 dark:text-white mb-2">{{ __('Impor Berhasil!') }}</h2>
                <p class="text-slate-500 dark:text-slate-400 text-lg mb-8">
                    Sistem berhasil mengimpor <strong>{{ $importedCount }}</strong> transaksi ke dalam dompet Anda.
                </p>
                <div class="flex items-center justify-center gap-4">
                    <button wire:click="resetImporter" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-xl transition-colors">
                        {{ __('Impor File Lain') }}
                    </button>
                    <a href="{{ route('transactions.index') }}" wire:navigate class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-lg shadow-indigo-500/30 transition-colors">
                        {{ __('Lihat Transaksi') }} &rarr;
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
