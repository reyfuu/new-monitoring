<?php

namespace App\Jobs;

use App\Mail\LaporanStatusMail;
use App\Models\Laporan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendLaporanStatusEmail implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public Laporan $laporan,
        public string $status,
        public ?string $komentar = null
    ) {}

    public function handle(): void
    {
        $mahasiswa = $this->laporan->mahasiswa;

        if ($mahasiswa && $mahasiswa->email) {
            Mail::to($mahasiswa->email)->send(
                new LaporanStatusMail($this->laporan, $this->status, $this->komentar)
            );
        }
    }
}
