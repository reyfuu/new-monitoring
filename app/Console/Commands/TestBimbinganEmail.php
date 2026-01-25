<?php

namespace App\Console\Commands;

use App\Jobs\SendBimbinganBaruEmail;
use App\Jobs\SendBimbinganStatusEmail;
use App\Models\Bimbingan;
use Illuminate\Console\Command;

class TestBimbinganEmail extends Command
{
    protected $signature = 'test:bimbingan-email {type=baru : Type of email (baru/disetujui/ditolak)}';

    protected $description = 'Test sending bimbingan email notifications';

    public function handle(): int
    {
        $type = $this->argument('type');

        // Cari bimbingan pertama yang ada
        $bimbingan = Bimbingan::with(['mahasiswa', 'dosen'])->first();

        if (!$bimbingan) {
            $this->error('❌ Tidak ada data bimbingan. Silakan buat bimbingan terlebih dahulu.');
            return Command::FAILURE;
        }

        $this->info("📧 Testing email dengan bimbingan ID: {$bimbingan->id}");
        $this->info("   Mahasiswa: {$bimbingan->mahasiswa?->name}");
        $this->info("   Dosen: {$bimbingan->dosen?->name}");
        $this->newLine();

        switch ($type) {
            case 'baru':
                $this->info('📤 Mengirim email "Bimbingan Baru" ke dosen...');
                SendBimbinganBaruEmail::dispatch($bimbingan);
                $this->info("✅ Job dispatched! Email akan dikirim ke: {$bimbingan->dosen?->email}");
                break;

            case 'disetujui':
                $this->info('📤 Mengirim email "Status Disetujui" ke mahasiswa...');
                SendBimbinganStatusEmail::dispatch($bimbingan, 'disetujui', 'Bagus! Lanjutkan ke tahap berikutnya.');
                $this->info("✅ Job dispatched! Email akan dikirim ke: {$bimbingan->mahasiswa?->email}");
                break;

            case 'ditolak':
                $this->info('📤 Mengirim email "Status Ditolak" ke mahasiswa...');
                SendBimbinganStatusEmail::dispatch($bimbingan, 'ditolak', 'Mohon perbaiki bagian metodologi penelitian.');
                $this->info("✅ Job dispatched! Email akan dikirim ke: {$bimbingan->mahasiswa?->email}");
                break;

            default:
                $this->error("❌ Type tidak valid. Gunakan: baru, disetujui, atau ditolak");
                return Command::FAILURE;
        }

        $this->newLine();
        $this->warn('⚠️  Pastikan queue worker sedang berjalan:');
        $this->line('   docker compose exec app php artisan queue:work');

        return Command::SUCCESS;
    }
}
