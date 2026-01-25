<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah status lama ke status baru:
     * - pending → review
     * - menunggu → review
     * - ditolak → revisi
     */
    public function up(): void
    {
        // Update status di tabel bimbingans
        DB::table('bimbingans')
            ->where('status', 'pending')
            ->update(['status' => 'review']);

        DB::table('bimbingans')
            ->where('status', 'menunggu')
            ->update(['status' => 'review']);

        DB::table('bimbingans')
            ->where('status', 'ditolak')
            ->update(['status' => 'revisi']);

        // Update status di tabel laporan_mingguans
        DB::table('laporan_mingguans')
            ->where('status', 'pending')
            ->update(['status' => 'review']);

        DB::table('laporan_mingguans')
            ->where('status', 'menunggu')
            ->update(['status' => 'review']);

        DB::table('laporan_mingguans')
            ->where('status', 'ditolak')
            ->update(['status' => 'revisi']);
    }

    /**
     * Reverse the migrations.
     * Mengembalikan status baru ke status lama:
     * - review → pending
     * - revisi → ditolak
     */
    public function down(): void
    {
        // Rollback status di tabel bimbingans
        DB::table('bimbingans')
            ->where('status', 'review')
            ->update(['status' => 'pending']);

        DB::table('bimbingans')
            ->where('status', 'revisi')
            ->update(['status' => 'ditolak']);

        // Rollback status di tabel laporan_mingguans
        DB::table('laporan_mingguans')
            ->where('status', 'review')
            ->update(['status' => 'pending']);

        DB::table('laporan_mingguans')
            ->where('status', 'revisi')
            ->update(['status' => 'ditolak']);
    }
};
