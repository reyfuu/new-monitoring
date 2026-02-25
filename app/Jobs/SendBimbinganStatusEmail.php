<?php

namespace App\Jobs;

use App\Mail\BimbinganStatusMail;
use App\Models\Bimbingan;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendBimbinganStatusEmail
{
    use Dispatchable;

    public function __construct(
        public Bimbingan $bimbingan,
        public string $status,
        public ?string $komentar = null
    ) {}

    public function handle(): void
    {
        $mahasiswa = $this->bimbingan->mahasiswa;

        if ($mahasiswa && $mahasiswa->email) {
            try {
                Mail::to($mahasiswa->email)->send(
                    new BimbinganStatusMail($this->bimbingan, $this->status, $this->komentar)
                );
            } catch (\Exception $e) {
                Log::error('Bimbingan Status Email failed: ' . $e->getMessage());
            }
        }
    }
}
