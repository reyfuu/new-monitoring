<?php

namespace App\Jobs;

use App\Mail\BimbinganBaruMail;
use App\Models\Bimbingan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBimbinganBaruEmail implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public Bimbingan $bimbingan
    ) {}

    public function handle(): void
    {
        $dosen = $this->bimbingan->dosen;

        if ($dosen && $dosen->email) {
            Mail::to($dosen->email)->send(new BimbinganBaruMail($this->bimbingan));
        }
    }
}
