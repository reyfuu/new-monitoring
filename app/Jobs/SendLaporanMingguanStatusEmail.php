<?php

namespace App\Jobs;

use App\Models\LaporanMingguan;
use App\Mail\LaporanMingguanStatusMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMingguanStatusEmail implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public LaporanMingguan $laporanMingguan,
        public string $status,
    ) {}

    public function handle(): void
    {
        $mahasiswa = $this->laporanMingguan->mahasiswa;

        if ($mahasiswa && $mahasiswa->email) {
            Mail::to($mahasiswa->email)->send(
                new LaporanMingguanStatusMail($this->laporanMingguan, $this->status)
            );
        }
    }
}
