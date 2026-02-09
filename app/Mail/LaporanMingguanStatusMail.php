<?php

namespace App\Mail;

use App\Models\LaporanMingguan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LaporanMingguanStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LaporanMingguan $laporanMingguan,
        public string $status,
    ) {}

    public function envelope(): Envelope
    {
        $statusLabel = $this->status === 'disetujui' ? 'Disetujui' : 'Revisi';
        
        return new Envelope(
            subject: 'Status Laporan Mingguan Anda: ' . $statusLabel,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.laporan-mingguan-status',
            with: [
                'laporanMingguan' => $this->laporanMingguan,
                'mahasiswa' => $this->laporanMingguan->mahasiswa,
                'dosen' => $this->laporanMingguan->dosen,
                'status' => $this->status,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
