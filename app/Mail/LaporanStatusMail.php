<?php

namespace App\Mail;

use App\Models\Laporan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LaporanStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Laporan $laporan,
        public string $status,
        public ?string $komentar = null
    ) {}

    public function envelope(): Envelope
    {
        $statusLabel = $this->status === 'disetujui' ? 'Disetujui' : 'Revisi';
        
        return new Envelope(
            subject: 'Status Laporan Anda: ' . $statusLabel,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.laporan-status',
            with: [
                'laporan' => $this->laporan,
                'mahasiswa' => $this->laporan->mahasiswa,
                'dosen' => $this->laporan->dosen,
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
