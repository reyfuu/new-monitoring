<?php

namespace App\Jobs;

use App\Models\Laporan;
use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendLaporanStatusTelegram implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public Laporan $laporan,
        public string $status,
        public ?string $komentar = null
    ) {}

    public function handle(TelegramService $telegram): void
    {
        $mahasiswa = $this->laporan->mahasiswa;
        $dosen     = $this->laporan->dosen;

        $emoji = $this->status === 'disetujui' ? '✅' : '🔄';
        $label = ucfirst($this->status);

        $message = "{$emoji} <b>Status Laporan Diperbarui</b>\n\n"
            . "👤 <b>Mahasiswa:</b> " . ($mahasiswa?->name ?? '-') . "\n"
            . "👨‍🏫 <b>Dosen:</b> " . ($dosen?->name ?? '-') . "\n"
            . "📝 <b>Judul:</b> " . $this->laporan->judul . "\n"
            . "📂 <b>Tipe:</b> " . ucfirst($this->laporan->type ?? '-') . "\n"
            . "📊 <b>Status:</b> {$label}\n"
            . ($this->komentar ? "💬 <b>Komentar:</b> " . $this->komentar : '');

        $telegram->send($message);
    }
}
