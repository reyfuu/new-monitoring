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
            $table->unsignedBigInteger('bimbingan_id')->nullable()->after('laporan_id');
            
            // Foreign key ke tabel bimbingans
            $table->foreign('bimbingan_id')
                ->references('id')
                ->on('bimbingans')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_mingguans', function (Blueprint $table) {
            $table->dropForeign(['bimbingan_id']);
            $table->dropColumn('bimbingan_id');
        });
    }
};
