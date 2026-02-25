<?php

namespace App\Jobs;

use App\Mail\LaporanStatusMail;
use App\Models\Laporan;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendLaporanStatusEmail
{
    use Dispatchable;

    public function __construct(
        public Laporan $laporan,
        public string $status,
        public ?string $komentar = null
    ) {}

    public function handle(): void
    {
        $mahasiswa = $this->laporan->mahasiswa;

        if ($mahasiswa && $mahasiswa->email) {
            try {
                Mail::to($mahasiswa->email)->send(
                    new LaporanStatusMail($this->laporan, $this->status, $this->komentar)
                );
            } catch (\Exception $e) {
                Log::error('Laporan Status Email failed: ' . $e->getMessage());
            }
        }
    }
}
