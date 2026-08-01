<?php

namespace App\Livewire\AiAssistant;

use App\Models\AiChat;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ChatWindow extends Component
{
    public $message = '';
    public $chats = [];
    public $provider;
    public $isRateLimited = false;
    public $rateLimitRetryAfter = null;

    // Provider yang tersedia
    const PROVIDERS = [
        'gemini'  => ['label' => 'Gemini', 'icon' => 'G', 'color' => 'text-blue-500'],
        'claude'  => ['label' => 'Claude', 'icon' => 'C', 'color' => 'text-orange-500'],
        'openai'  => ['label' => 'ChatGPT', 'icon' => '⊕', 'color' => 'text-emerald-500'],
        'mistral' => ['label' => 'Mistral', 'icon' => 'M', 'color' => 'text-sky-500'],
    ];

    public function mount()
    {
        $this->provider = env('AI_PROVIDER', 'gemini');
        $this->loadChats();
    }

    public function render()
    {
        return view('livewire.ai-assistant.chat-window', [
            'providers' => self::PROVIDERS,
        ])->layout('layouts.app');
    }

    public function loadChats()
    {
        $this->chats = AiChat::where('user_id', Auth::id())
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();
    }

    public function switchProvider($prov)
    {
        if (array_key_exists($prov, self::PROVIDERS)) {
            $this->provider = $prov;
            $this->isRateLimited = false;
            $this->rateLimitRetryAfter = null;
            
            // Simpan provider yang dipilih ke file .env agar permanen
            $this->updateEnv(['AI_PROVIDER' => $prov]);
        }
    }

    private function updateEnv(array $data)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $env = file_get_contents($path);
            foreach ($data as $key => $value) {
                if (preg_match("/^{$key}=/m", $env)) {
                    $env = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $env);
                } else {
                    $env .= PHP_EOL . "{$key}={$value}" . PHP_EOL;
                }
            }
            file_put_contents($path, $env);
        }
    }

    public function sendMessage()
    {
        $this->validate(['message' => 'required|string|max:1000']);

        $userMessage = $this->message;
        $this->message = '';

        // Simpan pesan user
        $chat = AiChat::create([
            'user_id' => Auth::id(),
            'role'    => 'user',
            'message' => $userMessage,
        ]);
        $this->chats[] = $chat->toArray();

        // Siapkan konteks keuangan
        $systemInstruction = $this->buildSystemPrompt();

        // Ambil history (10 terakhir)
        $recentChats = AiChat::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->reverse();

        // Panggil AI sesuai provider aktif
        $result = match ($this->provider) {
            'claude'  => $this->callClaude($recentChats, $systemInstruction),
            'openai'  => $this->callOpenAI($recentChats, $systemInstruction),
            'mistral' => $this->callMistral($recentChats, $systemInstruction),
            default   => $this->callGemini($recentChats, $systemInstruction),
        };

        // Simpan respons AI
        $botChat = AiChat::create([
            'user_id' => Auth::id(),
            'role'    => 'model',
            'message' => $result['reply'],
        ]);
        $this->chats[] = $botChat->toArray();

        $this->isRateLimited      = $result['rate_limited'] ?? false;
        $this->rateLimitRetryAfter = $result['retry_after'] ?? null;
    }

    public function clearChat()
    {
        AiChat::where('user_id', Auth::id())->delete();
        $this->chats = [];
        $this->isRateLimited = false;
        $this->rateLimitRetryAfter = null;
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    private function buildSystemPrompt(): string
    {
        $totalBalance     = Wallet::where('user_id', Auth::id())->sum('balance');
        $incomeThisMonth  = Transaction::where('user_id', Auth::id())
            ->where('type', 'income')->whereMonth('transaction_date', now()->month)->sum('amount');
        $expenseThisMonth = Transaction::where('user_id', Auth::id())
            ->where('type', 'expense')->whereMonth('transaction_date', now()->month)->sum('amount');

        return "Anda adalah Asisten Keuangan Pribadi yang ramah, ringkas, dan selalu menjawab dalam bahasa Indonesia. "
            . "Data keuangan pengguna saat ini: "
            . "Saldo total: Rp " . number_format($totalBalance, 0, ',', '.') . ". "
            . "Pemasukan bulan ini: Rp " . number_format($incomeThisMonth, 0, ',', '.') . ". "
            . "Pengeluaran bulan ini: Rp " . number_format($expenseThisMonth, 0, ',', '.') . ". "
            . "Gunakan data ini bila relevan.";
    }

    private function callGemini($recentChats, string $system): array
    {
        $apiKey = env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            return ['reply' => '⚠️ API Key Gemini belum diatur di file .env (GEMINI_API_KEY).'];
        }

        $contents = [];
        foreach ($recentChats as $c) {
            $contents[] = [
                'role'  => $c->role === 'user' ? 'user' : 'model',
                'parts' => [['text' => $c->message]],
            ];
        }

        try {
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}",
                [
                    'contents'          => $contents,
                    'systemInstruction' => ['role' => 'system', 'parts' => [['text' => $system]]],
                ]
            );

            if ($response->status() === 429) {
                $retryAfter = $this->parseRetryAfter($response->json());
                return [
                    'reply'        => $this->rateLimitMessage('Gemini', $retryAfter),
                    'rate_limited' => true,
                    'retry_after'  => $retryAfter,
                ];
            }

            if ($response->successful()) {
                $data  = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? '(Tidak ada respons)';
                return ['reply' => $reply];
            }

            return ['reply' => '⚠️ Gemini gagal merespons. Coba ganti provider lain.'];
        } catch (\Exception $e) {
            return ['reply' => '⚠️ Gagal terhubung ke Gemini. Cek koneksi internet Anda.'];
        }
    }

    private function callClaude($recentChats, string $system): array
    {
        $apiKey = env('CLAUDE_API_KEY');
        if (empty($apiKey)) {
            return ['reply' => '⚠️ API Key Claude belum diatur di file .env (CLAUDE_API_KEY). Dapatkan key di: https://console.anthropic.com'];
        }

        $messages = [];
        foreach ($recentChats as $c) {
            $messages[] = [
                'role'    => $c->role === 'user' ? 'user' : 'assistant',
                'content' => $c->message,
            ];
        }

        try {
            $response = Http::timeout(30)->withHeaders([
                'x-api-key'         => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model'      => 'claude-3-haiku-20240307',
                'max_tokens' => 1024,
                'system'     => $system,
                'messages'   => $messages,
            ]);

            if ($response->status() === 429) {
                $retryAfter = $this->parseRetryAfterHeader($response);
                return [
                    'reply'        => $this->rateLimitMessage('Claude', $retryAfter),
                    'rate_limited' => true,
                    'retry_after'  => $retryAfter,
                ];
            }

            if ($response->successful()) {
                $data  = $response->json();
                $reply = $data['content'][0]['text'] ?? '(Tidak ada respons)';
                return ['reply' => $reply];
            }

            return ['reply' => '⚠️ Claude gagal merespons. Coba ganti provider lain.'];
        } catch (\Exception $e) {
            return ['reply' => '⚠️ Gagal terhubung ke Claude. Cek koneksi internet Anda.'];
        }
    }

    private function callOpenAI($recentChats, string $system): array
    {
        $apiKey = env('OPENAI_API_KEY');
        if (empty($apiKey)) {
            return ['reply' => '⚠️ API Key OpenAI belum diatur di file .env (OPENAI_API_KEY). Dapatkan key di: https://platform.openai.com/api-keys'];
        }

        $messages = [['role' => 'system', 'content' => $system]];
        foreach ($recentChats as $c) {
            $messages[] = [
                'role'    => $c->role === 'user' ? 'user' : 'assistant',
                'content' => $c->message,
            ];
        }

        try {
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type'  => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model'      => 'gpt-4o-mini',
                'messages'   => $messages,
                'max_tokens' => 1024,
            ]);

            if ($response->status() === 429) {
                $retryAfter = $this->parseRetryAfterHeader($response);
                return [
                    'reply'        => $this->rateLimitMessage('ChatGPT', $retryAfter),
                    'rate_limited' => true,
                    'retry_after'  => $retryAfter,
                ];
            }

            if ($response->successful()) {
                $data  = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? '(Tidak ada respons)';
                return ['reply' => $reply];
            }

            return ['reply' => '⚠️ ChatGPT gagal merespons. Coba ganti provider lain.'];
        } catch (\Exception $e) {
            return ['reply' => '⚠️ Gagal terhubung ke OpenAI. Cek koneksi internet Anda.'];
        }
    }

    private function callMistral($recentChats, string $system): array
    {
        $apiKey = env('MISTRAL_API_KEY');
        if (empty($apiKey)) {
            return ['reply' => '⚠️ API Key Mistral belum diatur di file .env (MISTRAL_API_KEY). Dapatkan key di: https://console.mistral.ai'];
        }

        $messages = [['role' => 'system', 'content' => $system]];
        foreach ($recentChats as $c) {
            $messages[] = [
                'role'    => $c->role === 'user' ? 'user' : 'assistant',
                'content' => $c->message,
            ];
        }

        try {
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type'  => 'application/json',
            ])->post('https://api.mistral.ai/v1/chat/completions', [
                'model'      => 'mistral-small-latest',
                'messages'   => $messages,
                'max_tokens' => 1024,
            ]);

            if ($response->status() === 429) {
                $retryAfter = $this->parseRetryAfterHeader($response);
                return [
                    'reply'        => $this->rateLimitMessage('Mistral', $retryAfter),
                    'rate_limited' => true,
                    'retry_after'  => $retryAfter,
                ];
            }

            if ($response->successful()) {
                $data  = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? '(Tidak ada respons)';
                return ['reply' => $reply];
            }

            return ['reply' => '⚠️ Mistral gagal merespons. Coba ganti provider lain.'];
        } catch (\Exception $e) {
            return ['reply' => '⚠️ Gagal terhubung ke Mistral. Cek koneksi internet Anda.'];
        }
    }

    private function parseRetryAfter(array $json): ?int
    {
        // Cek dari pesan error Gemini (e.g. "retry in 44.8s")
        $message = $json['error']['message'] ?? '';
        if (preg_match('/retry in (\d+(\.\d+)?)s/i', $message, $m)) {
            return (int) ceil((float) $m[1]);
        }
        return null;
    }

    private function parseRetryAfterHeader($response): ?int
    {
        $header = $response->header('Retry-After');
        return $header ? (int) $header : null;
    }

    private function rateLimitMessage(string $providerName, ?int $retrySeconds): string
    {
        $timeText = $retrySeconds
            ? ($retrySeconds >= 60
                ? 'sekitar ' . ceil($retrySeconds / 60) . ' menit lagi'
                : "sekitar {$retrySeconds} detik lagi")
            : 'beberapa saat';

        return "⏳ **Kuota {$providerName} habis.** Coba lagi {$timeText}, atau pilih provider AI lain di atas.";
    }
}
