<?php

namespace App\Jobs;

use App\Models\Bimbingan;
use App\Services\TelegramService;
use Illuminate\Foundation\Bus\Dispatchable;

class SendBimbinganStatusTelegram
{
    use Dispatchable;

    public function __construct(
        public Bimbingan $bimbingan,
        public string $status,
        public ?string $komentar = null,
        public bool $isNew = false
    ) {}

    public function handle(TelegramService $telegram): void
    {
        $targetUser = null;
        $title = "Status Bimbingan Diperbarui";
        $emoji = "📌";

        if (in_array($this->status, ['disetujui', 'revisi'])) {
            $targetUser = $this->bimbingan->mahasiswa;
            $emoji = $this->status === 'disetujui' ? '✅' : '🔄';
        } elseif ($this->status === 'review') {
            $targetUser = $this->bimbingan->dosen;
            if ($this->isNew) {
                $title = "Bimbingan Baru Diajukan";
                $emoji = "📥";
            } else {
                $title = "Update Revisi Bimbingan";
                $emoji = "📤";
            }
        }

        if (!$targetUser || !$targetUser->telegram_chat_id) {
            \Illuminate\Support\Facades\Log::warning("Telegram skipped for Bimbingan ID {$this->bimbingan->id}: Target user or Chat ID missing.");
            return;
        }

        $statusLabel = match(strtolower(trim($this->status))) {
            'review' => 'Menunggu Review',
            'disetujui' => 'Disetujui',
            'revisi' => 'Perlu Revisi',
            default => ucfirst($this->status)
        };

        $message = "{$emoji} <b>{$title}</b>\n\n"
            . "👤 <b>Mahasiswa:</b> " . ($this->bimbingan->mahasiswa?->name ?? '-') . "\n"
            . "👨‍🏫 <b>Dosen:</b> " . ($this->bimbingan->dosen?->name ?? '-') . "\n"
            . "📌 <b>Topik:</b> " . $this->bimbingan->topik . "\n"
            . "📊 <b>Status:</b> {$statusLabel}\n"
            . ($this->komentar ? "💬 <b>Komentar:</b> " . $this->komentar : '');

        $telegram->send($message, $targetUser->telegram_chat_id);
    }
}
