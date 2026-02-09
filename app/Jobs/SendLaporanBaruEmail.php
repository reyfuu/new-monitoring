<?php

namespace App\Jobs;

use App\Mail\LaporanBaruMail;
use App\Models\Laporan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendLaporanBaruEmail implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public Laporan $laporan
    ) {}

    public function handle(): void
    {
        $dosen = $this->laporan->dosen;

        if ($dosen && $dosen->email) {
            Mail::to($dosen->email)->send(new LaporanBaruMail($this->laporan));
        }
    }
}
