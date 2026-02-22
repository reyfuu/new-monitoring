<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $token;
    protected string $chatId;

    public function __construct()
    {
        $this->token  = config('services.telegram.token', '');
        $this->chatId = config('services.telegram.chat_id', '');
    }

    public function send(string $message): void
    {
        if (empty($this->token) || empty($this->chatId)) {
            Log::warning('Telegram not configured: TELEGRAM_BOT_TOKEN or TELEGRAM_CHAT_ID is missing.');
            return;
        }

        try {
            Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", [
                'chat_id'    => $this->chatId,
                'text'       => $message,
                'parse_mode' => 'HTML',
            ]);
        } catch (\Throwable $e) {
            Log::error('Telegram send failed: ' . $e->getMessage());
        }
    }
}
