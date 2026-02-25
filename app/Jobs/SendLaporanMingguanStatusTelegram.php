<?php

namespace App\Jobs;

use App\Models\LaporanMingguan;
use App\Services\TelegramService;
use Illuminate\Foundation\Bus\Dispatchable;

class SendLaporanMingguanStatusTelegram
{
    use Dispatchable;

    public function __construct(
        public LaporanMingguan $laporanMingguan,
        public string $status,
        public ?string $komentar = null,
    ) {}

    public function handle(TelegramService $telegram): void
    {
        $targetUser = null;
        $title = "Status Laporan Mingguan Diperbarui";
        $emoji = "📅";

        if (in_array($this->status, ['disetujui', 'revisi'])) {
            $targetUser = $this->laporanMingguan->mahasiswa;
            $emoji = $this->status === 'disetujui' ? '✅' : '🔄';
        } elseif ($this->status === 'review') {
            $targetUser = $this->laporanMingguan->dosen;
            $title = "Update Revisi Laporan Mingguan";
            $emoji = "📥";
        }

        if (!$targetUser || !$targetUser->telegram_chat_id) {
            return;
        }

        $label = ucfirst($this->status);

        $message = "{$emoji} <b>{$title}</b>\n\n"
            . "👤 <b>Mahasiswa:</b> " . ($this->laporanMingguan->mahasiswa?->name ?? '-') . "\n"
            . "👨‍🏫 <b>Dosen:</b> " . ($this->laporanMingguan->dosen?->name ?? '-') . "\n"
            . "📅 <b>Minggu:</b> " . $this->laporanMingguan->week . "\n"
            . "📊 <b>Status:</b> {$label}";

        if ($this->komentar) {
            $message .= "\n💬 <b>Komentar:</b> {$this->komentar}";
        }

        $telegram->send($message, $targetUser->telegram_chat_id);
    }
}
