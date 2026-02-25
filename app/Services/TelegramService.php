<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $token;
    protected ?string $defaultChatId;

    public function __construct()
    {
        $this->token = config('services.telegram.token');
        $this->defaultChatId = config('services.telegram.chat_id');
    }

    /**
     * Send a message to a specific Telegram Chat ID or the default group.
     */
    public function send(string $message, ?string $chatId = null): void
    {
        $targetChatId = $chatId ?? $this->defaultChatId;

        if (!$this->token || !$targetChatId) {
            Log::warning('Telegram Notification skipped: Token or Chat ID not found for target recipient.');
            return;
        }

        try {
            \Illuminate\Support\Facades\Log::info("Telegram Attempt: ChatID: {$targetChatId}, Message: " . substr($message, 0, 50) . "...");

            $response = Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", [
                'chat_id'    => $targetChatId,
                'text'       => $message,
                'parse_mode' => 'HTML',
            ]);

            \Illuminate\Support\Facades\Log::info("Telegram Response: Status: " . $response->status() . " Body: " . $response->body());

            if ($response->failed()) {
                Log::error('Telegram API error: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Telegram connection error: ' . $e->getMessage());
        }
    }
}
