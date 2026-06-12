<div>
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Backup & Sinkronisasi</h1>
        <p class="mt-1 text-sm text-slate-400">Ekspor data Anda untuk cadangan, atau impor untuk memulihkan data.</p>
    </div>

    {{-- Success Message --}}
    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-medium flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Export Section --}}
        <div class="bg-slate-900/80 backdrop-blur-xl border border-white/[0.06] rounded-2xl p-8 flex flex-col justify-between shadow-lg shadow-indigo-500/5 hover:scale-[1.01] transition-transform duration-200">
            <div>
                {{-- Icon --}}
                <div class="w-14 h-14 bg-indigo-500/10 text-indigo-400 rounded-2xl flex items-center justify-center mb-5 ring-1 ring-indigo-500/20">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-white mb-2">Ekspor Data (Backup)</h2>
                <p class="text-slate-400 mb-8 leading-relaxed">Unduh seluruh data keuangan Anda (Dompet, Transaksi, Anggaran, Kategori) dalam format JSON yang aman. Simpan file ini di tempat yang aman sebagai cadangan jika terjadi sesuatu.</p>
            </div>
            <button wire:click="exportData" class="w-full py-3.5 bg-white/5 border-2 border-indigo-500/50 text-indigo-400 rounded-xl hover:bg-indigo-500/10 hover:border-indigo-400/60 transition-all duration-200 font-bold flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Buat Backup (.json)
            </button>
        </div>

        {{-- Import Section --}}
        <div class="bg-slate-900/80 backdrop-blur-xl border border-white/[0.06] rounded-2xl p-8 flex flex-col justify-between shadow-lg shadow-rose-500/5 hover:scale-[1.01] transition-transform duration-200">
            <div>
                {{-- Icon --}}
                <div class="w-14 h-14 bg-rose-500/10 text-rose-400 rounded-2xl flex items-center justify-center mb-5 ring-1 ring-rose-500/20">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-white mb-2">Impor Data (Restore)</h2>

                {{-- Warning Banner --}}
                <div class="bg-amber-500/10 border-l-4 border-amber-500 p-4 mb-6 rounded-r-xl">
                    <p class="text-sm text-amber-300 font-medium leading-relaxed">
                        <span class="font-bold block mb-1">PERINGATAN!</span> Mengimpor data akan MENGHAPUS & MENGGANTIKAN seluruh data keuangan Anda yang ada saat ini. Pastikan file JSON yang diunggah valid.
                    </p>
                </div>
                
                <form wire:submit.prevent="importData">
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-semibold text-white" for="file_input">Pilih File Backup (.json)</label>
                        <input wire:model="backupFile" class="block w-full text-sm text-slate-400 border border-white/10 rounded-xl cursor-pointer bg-slate-800/50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 p-2.5 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 transition-all duration-200" id="file_input" type="file" accept=".json">
                        @error('backupFile') <span class="text-rose-400 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    @if($importStatus)
                        <p class="text-sm text-indigo-400 mb-4 font-medium animate-pulse">{{ $importStatus }}</p>
                    @endif

                    <button type="submit" wire:loading.attr="disabled" class="w-full py-3.5 bg-rose-600 text-white rounded-xl hover:bg-rose-500 transition-all duration-200 font-bold flex items-center justify-center gap-2 shadow-lg shadow-rose-500/25 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="importData" class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                            Mulai Restore Data
                        </span>
                        <span wire:loading wire:target="importData" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Memproses...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
