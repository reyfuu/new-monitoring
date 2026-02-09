<?php

namespace App\Mail;

use App\Models\Laporan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LaporanBaruMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Laporan $laporan
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Laporan Baru dari ' . $this->laporan->mahasiswa->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.laporan-baru',
            with: [
                'laporan' => $this->laporan,
                'mahasiswa' => $this->laporan->mahasiswa,
                'dosen' => $this->laporan->dosen,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
