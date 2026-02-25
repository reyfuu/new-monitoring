<?php

namespace App\Jobs;

use App\Mail\LaporanMingguanBaruMail;
use App\Models\LaporanMingguan;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendLaporanMingguanBaruEmail
{
    use Dispatchable;

    public function __construct(
        public LaporanMingguan $laporanMingguan
    ) {}

    public function handle(): void
    {
        $dosen = $this->laporanMingguan->dosen;

        if ($dosen && $dosen->email) {
            try {
                Mail::to($dosen->email)->send(new LaporanMingguanBaruMail($this->laporanMingguan));
            } catch (\Exception $e) {
                Log::error('Laporan Mingguan Baru Email failed: ' . $e->getMessage());
            }
        }
    }
}
