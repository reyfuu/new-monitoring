<?php

namespace App\Jobs;

use App\Models\LaporanMingguan;
use App\Mail\LaporanMingguanStatusMail;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendLaporanMingguanStatusEmail
{
    use Dispatchable;

    public function __construct(
        public LaporanMingguan $laporanMingguan,
        public string $status,
        public ?string $komentar = null,
    ) {}

    public function handle(): void
    {
        $mahasiswa = $this->laporanMingguan->mahasiswa;

        if ($mahasiswa && $mahasiswa->email) {
            try {
                Mail::to($mahasiswa->email)->send(
                    new LaporanMingguanStatusMail($this->laporanMingguan, $this->status, $this->komentar)
                );
            } catch (\Exception $e) {
                Log::error('Laporan Mingguan Status Email failed: ' . $e->getMessage());
            }
        }
    }
}
