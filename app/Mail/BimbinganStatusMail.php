<?php

namespace App\Mail;

use App\Models\Bimbingan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BimbinganStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Bimbingan $bimbingan,
        public string $status,
        public ?string $komentar = null
    ) {}

    public function envelope(): Envelope
    {
        $statusLabel = $this->status === 'disetujui' ? 'Disetujui' : 'Revisi';
        
        return new Envelope(
            subject: 'Status Bimbingan Anda: ' . $statusLabel,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.bimbingan-status',
            with: [
                'bimbingan' => $this->bimbingan,
                'mahasiswa' => $this->bimbingan->mahasiswa,
                'dosen' => $this->bimbingan->dosen,
                'status' => $this->status,
                'komentar' => $this->komentar,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
