<?php

namespace App\Mail;

use App\Models\Bimbingan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BimbinganBaruMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Bimbingan $bimbingan
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bimbingan Baru dari ' . $this->bimbingan->mahasiswa->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.bimbingan-baru',
            with: [
                'bimbingan' => $this->bimbingan,
                'mahasiswa' => $this->bimbingan->mahasiswa,
                'dosen' => $this->bimbingan->dosen,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
