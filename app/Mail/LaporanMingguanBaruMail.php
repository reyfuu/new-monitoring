<?php

namespace App\Mail;

use App\Models\LaporanMingguan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LaporanMingguanBaruMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LaporanMingguan $laporanMingguan
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Laporan Mingguan Baru dari ' . $this->laporanMingguan->mahasiswa->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.laporan-mingguan-baru',
            with: [
                'laporanMingguan' => $this->laporanMingguan,
                'mahasiswa' => $this->laporanMingguan->mahasiswa,
                'dosen' => $this->laporanMingguan->dosen,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
