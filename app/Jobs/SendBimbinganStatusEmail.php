<?php

namespace App\Jobs;

use App\Mail\BimbinganStatusMail;
use App\Models\Bimbingan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBimbinganStatusEmail implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public Bimbingan $bimbingan,
        public string $status,
        public ?string $komentar = null
    ) {}

    public function handle(): void
    {
        $mahasiswa = $this->bimbingan->mahasiswa;

        if ($mahasiswa && $mahasiswa->email) {
            Mail::to($mahasiswa->email)->send(
                new BimbinganStatusMail($this->bimbingan, $this->status, $this->komentar)
            );
        }
    }
}
