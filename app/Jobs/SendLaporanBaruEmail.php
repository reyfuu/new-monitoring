<?php

namespace App\Jobs;

use App\Mail\LaporanBaruMail;
use App\Models\Laporan;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendLaporanBaruEmail
{
    use Dispatchable;

    public function __construct(
        public Laporan $laporan
    ) {}

    public function handle(): void
    {
        $dosen = $this->laporan->dosen;

        if ($dosen && $dosen->email) {
            try {
                Mail::to($dosen->email)->send(new LaporanBaruMail($this->laporan));
            } catch (\Exception $e) {
                Log::error('Laporan Baru Email failed: ' . $e->getMessage());
            }
        }
    }
}
