<?php

namespace App\Jobs;

use App\Models\Bimbingan;
use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBimbinganTelegram implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public Bimbingan $bimbingan
    ) {}

    public function handle(TelegramService $telegram): void
    {
        $mahasiswa = $this->bimbingan->mahasiswa;
        $dosen     = $this->bimbingan->dosen;

        $message = "📋 <b>Bimbingan Baru Diajukan</b>\n\n"
            . "👤 <b>Mahasiswa:</b> " . ($mahasiswa?->name ?? '-') . "\n"
            . "👨‍🏫 <b>Dosen:</b> " . ($dosen?->name ?? '-') . "\n"
            . "📌 <b>Topik:</b> " . $this->bimbingan->topik . "\n"
            . "📅 <b>Tanggal:</b> " . ($this->bimbingan->tanggal?->format('d M Y') ?? '-');

        $telegram->send($message);
    }
}
