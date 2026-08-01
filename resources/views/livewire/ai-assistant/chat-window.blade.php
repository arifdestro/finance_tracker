<div>
    {{-- Header --}}
    <div class="mb-5 flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center shadow-lg shadow-indigo-500/25">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                {{ __('AI Financial Assistant') }}
            </h1>
            <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400 ml-[46px]">{{ __('Tanya saya tentang keuangan Anda, analisis, atau sekadar tips berhemat.') }}</p>
        </div>
        <button wire:click="clearChat" wire:confirm="{{ __('Yakin ingin menghapus riwayat chat?') }}" class="p-2.5 text-slate-400 hover:text-rose-400 rounded-xl hover:bg-rose-500/10 transition-all duration-200" title="{{ __('Hapus riwayat') }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
    </div>

    {{-- Main Chat Card --}}
    <div class="bg-white/80 dark:bg-slate-900/60 backdrop-blur-2xl border border-slate-200 dark:border-white/[0.06] rounded-2xl overflow-hidden shadow-xl shadow-slate-900/5 dark:shadow-black/20 flex flex-col" style="height: calc(100vh - 240px); min-height: 400px; max-height: 700px;">

        {{-- Provider Switcher Bar --}}
        <div class="px-4 py-3 border-b border-slate-100 dark:border-white/[0.04] bg-slate-50/80 dark:bg-slate-800/40 flex items-center gap-2 flex-wrap">
            <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-widest mr-1">AI</span>

            @foreach($providers as $key => $info)
                <button
                    wire:click="switchProvider('{{ $key }}')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-200
                        {{ $provider === $key
                            ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/25 ring-1 ring-indigo-500/50'
                            : 'bg-white dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 ring-1 ring-slate-200 dark:ring-slate-600/50 hover:ring-indigo-300 dark:hover:ring-indigo-500/50 hover:text-indigo-600 dark:hover:text-indigo-400' }}"
                >
                    <span class="{{ $provider === $key ? 'text-indigo-200' : $info['color'] }} text-[10px] font-black">{{ $info['icon'] }}</span>
                    {{ $info['label'] }}
                </button>
            @endforeach

            {{-- Rate limit badge --}}
            @if($isRateLimited)
                <div class="ml-auto flex items-center gap-1.5 px-2.5 py-1 bg-amber-500/10 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[11px] font-medium rounded-lg ring-1 ring-amber-500/20">
                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ __('Limit') }}{{ $rateLimitRetryAfter ? ' · ' . ($rateLimitRetryAfter >= 60 ? ceil($rateLimitRetryAfter/60).'m' : $rateLimitRetryAfter.'s') : '' }}
                </div>
            @endif
        </div>

        {{-- Chat History --}}
        <div class="flex-1 px-5 py-5 overflow-y-auto custom-scrollbar" id="chat-container">
            @if(count($chats) === 0)
                <div class="h-full flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500/20 to-fuchsia-500/20 rounded-2xl flex items-center justify-center mb-4 ring-1 ring-indigo-500/10">
                        <svg class="w-8 h-8 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700 dark:text-slate-200 mb-1.5">{{ __('Halo, saya AI Keuangan Anda!') }}</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs leading-relaxed">{{ __('Coba tanyakan: "Berapa pengeluaran bulan ini?" atau "Tips menabung 5 juta setahun?"') }}</p>
                </div>
            @endif

            <div class="space-y-4">
                @foreach($chats as $chat)
                    @if($chat['role'] === 'user')
                        {{-- User bubble --}}
                        <div class="flex justify-end">
                            <div class="bg-indigo-600 text-white px-4 py-3 rounded-2xl rounded-br-md max-w-[75%] shadow-sm shadow-indigo-500/20">
                                <p class="text-[13px] whitespace-pre-wrap leading-relaxed">{{ $chat['message'] }}</p>
                            </div>
                        </div>
                    @else
                        {{-- AI bubble --}}
                        <div class="flex justify-start gap-2.5 group/ai">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div class="relative max-w-[75%]">
                                <div class="bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 px-4 py-3 rounded-2xl rounded-bl-md ring-1 ring-slate-200/50 dark:ring-white/[0.04]">
                                    <div class="ai-content text-[13px] leading-relaxed">
                                        {!! \Illuminate\Support\Str::markdown($chat['message'], ['html_input' => 'escape']) !!}
                                    </div>
                                </div>
                                {{-- Copy button --}}
                                <button
                                    x-data="{ copied: false }"
                                    x-on:click="
                                        navigator.clipboard.writeText(@js($chat['message']));
                                        copied = true;
                                        setTimeout(() => copied = false, 1500);
                                    "
                                    class="absolute -bottom-3 right-2 opacity-0 group-hover/ai:opacity-100 transition-all duration-200 flex items-center gap-1 px-2 py-1 rounded-lg bg-white dark:bg-slate-700 ring-1 ring-slate-200 dark:ring-slate-600 shadow-sm text-[11px] font-medium text-slate-500 dark:text-slate-400 hover:text-indigo-500 dark:hover:text-indigo-400 hover:ring-indigo-300 dark:hover:ring-indigo-500/50"
                                >
                                    <template x-if="!copied">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                                    </template>
                                    <template x-if="copied">
                                        <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </template>
                                    <span x-text="copied ? '{{ __('Tersalin!') }}' : '{{ __('Salin') }}'"></span>
                                </button>
                            </div>
                        </div>
                    @endif
                @endforeach

                {{-- Typing indicator --}}
                <div wire:loading wire:target="sendMessage" class="flex justify-start gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div class="bg-slate-100 dark:bg-slate-800/80 px-5 py-3.5 rounded-2xl rounded-bl-md ring-1 ring-slate-200/50 dark:ring-white/[0.04] flex gap-1.5 items-center">
                        <div class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                        <div class="w-1.5 h-1.5 bg-fuchsia-500 rounded-full animate-bounce" style="animation-delay: 0.15s;"></div>
                        <div class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 0.3s;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Area --}}
        <div class="p-3 border-t border-slate-100 dark:border-white/[0.04] bg-slate-50/80 dark:bg-slate-800/30">
            <form wire:submit.prevent="sendMessage" class="flex items-end gap-2">
                <div class="flex-1 relative">
                    <textarea
                        wire:model="message"
                        rows="1"
                        class="w-full bg-white dark:bg-slate-800/80 border-0 ring-1 ring-slate-200 dark:ring-slate-600 text-slate-800 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-indigo-500/50 block px-4 py-3 text-[13px] transition-all resize-none custom-scrollbar min-h-[44px] placeholder:text-slate-400 dark:placeholder:text-slate-400"
                        placeholder="{{ __('Tanyakan tentang keuangan Anda...') }}"
                        required
                        x-data="{ resize: () => { $el.style.height = 'auto'; $el.style.height = ($el.scrollHeight < 120 ? $el.scrollHeight : 120) + 'px' } }"
                        x-init="resize()"
                        @input="resize()"
                        @keydown.enter.prevent="if(!$event.shiftKey) $wire.sendMessage()"
                    ></textarea>
                </div>

                <button type="submit"
                    class="flex-shrink-0 w-[44px] h-[44px] bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white rounded-xl shadow-lg shadow-indigo-500/25 transition-all duration-200 flex items-center justify-center group"
                    wire:loading.attr="disabled"
                    wire:loading.class="!bg-indigo-400 cursor-wait"
                    wire:target="sendMessage"
                >
                    <svg wire:loading.remove wire:target="sendMessage" class="w-4.5 h-4.5 transform rotate-90 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    <svg wire:loading wire:target="sendMessage" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                </button>
            </form>
        </div>
    </div>

    {{-- Auto-scroll script --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            const container = document.getElementById('chat-container');
            const scrollToBottom = () => { if(container) container.scrollTop = container.scrollHeight; }
            scrollToBottom();
            Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                succeed(({ snapshot, effect }) => { setTimeout(scrollToBottom, 50); });
            });
        });
    </script>

    <style>
    .ai-content p { margin-bottom: 0.75em; }
    .ai-content p:last-child { margin-bottom: 0; }
    .ai-content strong { font-weight: 600; color: inherit; }
    .ai-content h3 { font-weight: 700; font-size: 14px; margin-top: 1.2em; margin-bottom: 0.5em; color: inherit; }
    .ai-content ul { list-style-type: disc; padding-left: 1.5em; margin-bottom: 0.75em; }
    .ai-content ol { list-style-type: decimal; padding-left: 1.5em; margin-bottom: 0.75em; }
    .ai-content li { margin-bottom: 0.25em; }
    .ai-content li::marker { color: #64748b; }
    .ai-content code { font-family: monospace; background: rgba(150, 150, 150, 0.15); padding: 0.1em 0.3em; border-radius: 4px; font-size: 0.9em; }
    .dark .ai-content li::marker { color: #94a3b8; }
    </style>
</div>
