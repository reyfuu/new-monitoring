<?php

namespace App\Jobs;

use App\Mail\LaporanMingguanBaruMail;
use App\Models\LaporanMingguan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendLaporanMingguanBaruEmail implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public LaporanMingguan $laporanMingguan
    ) {}

    public function handle(): void
    {
        $dosen = $this->laporanMingguan->dosen;

        if ($dosen && $dosen->email) {
            Mail::to($dosen->email)->send(new LaporanMingguanBaruMail($this->laporanMingguan));
        }
    }
}
