<?php

namespace App\Jobs;

use App\Models\Laporan;
use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendLaporanTelegram implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public Laporan $laporan
    ) {}

    public function handle(TelegramService $telegram): void
    {
        $mahasiswa = $this->laporan->mahasiswa;
        $dosen     = $this->laporan->dosen;

        $tipeLabel = ucfirst($this->laporan->type ?? '-');

        $message = "📄 <b>Laporan Baru Diajukan</b>\n\n"
            . "👤 <b>Mahasiswa:</b> " . ($mahasiswa?->name ?? '-') . "\n"
            . "👨‍🏫 <b>Dosen:</b> " . ($dosen?->name ?? '-') . "\n"
            . "📝 <b>Judul:</b> " . $this->laporan->judul . "\n"
            . "📂 <b>Tipe:</b> {$tipeLabel}";

        $telegram->send($message);
    }
}
