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
        public ?string $komentar = null,
        public bool $isNew = false
    ) {}

    public function handle(TelegramService $telegram): void
    {
        $targetUser = null;
        $typeLabel = match($this->laporan->type) {
            'proposal' => 'Proposal',
            'magang' => 'Laporan Magang',
            'skripsi' => 'Laporan Akhir (Skripsi)',
            default => 'Laporan'
        };

        $title = "Status {$typeLabel} Diperbarui";
        $emoji = "📄";

        if (in_array($this->status, ['disetujui', 'revisi'])) {
            $targetUser = $this->laporan->mahasiswa;
            $emoji = $this->status === 'disetujui' ? '✅' : '🔄';
        } elseif ($this->status === 'review') {
            $targetUser = $this->laporan->dosen;
            if ($this->isNew) {
                $title = "{$typeLabel} Baru Diajukan";
                $emoji = "📥";
            } else {
                $title = "Update Revisi {$typeLabel}";
                $emoji = "📤";
            }
        }

        // Send to target user if chat ID is set
        if ($targetUser && $targetUser->telegram_chat_id) {
            $message = $this->formatMessage($title, $emoji, $typeLabel);
            $telegram->send($message, $targetUser->telegram_chat_id);
        } else {
            \Illuminate\Support\Facades\Log::warning("Telegram skipped for " . ($targetUser?->name ?? 'unknown') . ": Chat ID missing.");
        }

        // Always send "New Submission" of Proposal/Skripsi to default admin chat if configured
        if ($this->isNew && in_array($this->laporan->type, ['proposal', 'skripsi'])) {
            $adminMessage = "📢 <b>Notifikasi Admin</b>\n" . $this->formatMessage($title, $emoji, $typeLabel);
            $telegram->send($adminMessage); // Will use defaultChatId
        }
    }

    protected function formatMessage(string $title, string $emoji, string $typeLabel): string
    {
        $statusLabel = match(strtolower(trim($this->status))) {
            'review' => 'Menunggu Review',
            'disetujui' => 'Disetujui',
            'revisi' => 'Perlu Revisi',
            default => ucfirst($this->status)
        };

        return "{$emoji} <b>{$title}</b>\n\n"
            . "👤 <b>Mahasiswa:</b> " . ($this->laporan->mahasiswa?->name ?? '-') . "\n"
            . "👨‍🏫 <b>Dosen:</b> " . ($this->laporan->dosen?->name ?? '-') . "\n"
            . "📌 <b>Judul:</b> " . $this->laporan->judul . "\n"
            . "📊 <b>Status:</b> {$statusLabel}\n"
            . ($this->komentar ? "💬 <b>Komentar:</b> " . $this->komentar : '');
    }
}
