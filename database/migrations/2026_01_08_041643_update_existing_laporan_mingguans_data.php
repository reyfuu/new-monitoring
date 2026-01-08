<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update data lama: ambil mahasiswa_id dan dosen_id dari relasi laporan
        $laporanMingguans = \App\Models\LaporanMingguan::whereNull('mahasiswa_id')
            ->orWhereNull('dosen_id')
            ->get();

        foreach ($laporanMingguans as $lm) {
            if ($lm->laporan_id && $lm->laporan) {
                $lm->mahasiswa_id = $lm->laporan->mahasiswa_id;
                $lm->dosen_id = $lm->laporan->dosen_id;
                $lm->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback untuk data update
    }
};
