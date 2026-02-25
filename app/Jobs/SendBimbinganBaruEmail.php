<?php

namespace App\Jobs;

use App\Mail\BimbinganBaruMail;
use App\Models\Bimbingan;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendBimbinganBaruEmail
{
    use Dispatchable;

    public function __construct(
        public Bimbingan $bimbingan
    ) {}

    public function handle(): void
    {
        $dosen = $this->bimbingan->dosen;

        if ($dosen && $dosen->email) {
            try {
                Mail::to($dosen->email)->send(new BimbinganBaruMail($this->bimbingan));
            } catch (\Exception $e) {
                Log::error('Bimbingan Baru Email failed: ' . $e->getMessage());
            }
        }
    }
}
