<?php

namespace App\Jobs;

use App\Models\Bimbingan;
use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBimbinganStatusTelegram implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public Bimbingan $bimbingan,
        public string $status,
        public ?string $komentar = null
    ) {}

    public function handle(TelegramService $telegram): void
    {
        $mahasiswa = $this->bimbingan->mahasiswa;
        $dosen     = $this->bimbingan->dosen;

        $emoji = $this->status === 'disetujui' ? '✅' : '🔄';
        $label = ucfirst($this->status);

        $message = "{$emoji} <b>Status Bimbingan Diperbarui</b>\n\n"
            . "👤 <b>Mahasiswa:</b> " . ($mahasiswa?->name ?? '-') . "\n"
            . "👨‍🏫 <b>Dosen:</b> " . ($dosen?->name ?? '-') . "\n"
            . "📌 <b>Topik:</b> " . $this->bimbingan->topik . "\n"
            . "📊 <b>Status:</b> {$label}\n"
            . ($this->komentar ? "💬 <b>Komentar:</b> " . $this->komentar : '');

        $telegram->send($message);
    }
}
