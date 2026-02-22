<?php

namespace App\Jobs;

use App\Models\LaporanMingguan;
use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendLaporanMingguanTelegram implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public LaporanMingguan $laporanMingguan
    ) {}

    public function handle(TelegramService $telegram): void
    {
        $mahasiswa = $this->laporanMingguan->mahasiswa;
        $dosen     = $this->laporanMingguan->dosen;

        $message = "📅 <b>Laporan Mingguan Baru</b>\n\n"
            . "👤 <b>Mahasiswa:</b> " . ($mahasiswa?->name ?? '-') . "\n"
            . "👨‍🏫 <b>Dosen:</b> " . ($dosen?->name ?? '-') . "\n"
            . "📆 <b>Minggu ke:</b> " . ($this->laporanMingguan->week ?? '-');

        $telegram->send($message);
    }
}
