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
        Schema::table('laporan_mingguans', function (Blueprint $table) {
            $table->unsignedBigInteger('mahasiswa_id')->nullable()->after('laporan_id');
            $table->unsignedBigInteger('dosen_id')->nullable()->after('mahasiswa_id');

            $table->foreign('mahasiswa_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('dosen_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_mingguans', function (Blueprint $table) {
            $table->dropForeign(['mahasiswa_id']);
            $table->dropForeign(['dosen_id']);
            $table->dropColumn(['mahasiswa_id', 'dosen_id']);
        });
    }
};
