<?php

namespace App\Jobs;

use App\Models\Laporan;
use App\Services\TelegramService;
use Illuminate\Foundation\Bus\Dispatchable;

class SendLaporanStatusTelegram
{
    use Dispatchable;

    public function __construct(
        public Laporan $laporan,
        public string $status,
        public ?string $komentar = null
    ) {}

    public function handle(TelegramService $telegram): void
    {
        $targetUser = null;
        $title = "Status Laporan Diperbarui";
        $emoji = "📄";

        if (in_array($this->status, ['disetujui', 'revisi'])) {
            $targetUser = $this->laporan->mahasiswa;
            $emoji = $this->status === 'disetujui' ? '✅' : '🔄';
        } elseif ($this->status === 'review') {
            $targetUser = $this->laporan->dosen;
            $title = "Update Revisi Laporan";
            $emoji = "📥";
        }

        if (!$targetUser || !$targetUser->telegram_chat_id) {
            return;
        }

        $label = ucfirst($this->status);

        $message = "{$emoji} <b>{$title}</b>\n\n"
            . "👤 <b>Mahasiswa:</b> " . ($this->laporan->mahasiswa?->name ?? '-') . "\n"
            . "👨‍🏫 <b>Dosen:</b> " . ($this->laporan->dosen?->name ?? '-') . "\n"
            . "📌 <b>Judul:</b> " . $this->laporan->judul . "\n"
            . "📊 <b>Status:</b> {$label}\n"
            . ($this->komentar ? "💬 <b>Komentar:</b> " . $this->komentar : '');

        $telegram->send($message, $targetUser->telegram_chat_id);
    }
}
