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
        Schema::table('comments', function (Blueprint $table) {
            // Hapus kolom polymorphic
            $table->dropColumn(['commentable_id', 'commentable_type']);

            // Tambahkan Foreign Keys eksplisit
            $table->foreignId('bimbingan_id')->nullable()->constrained('bimbingans')->onDelete('cascade');
            $table->foreignId('laporan_id')->nullable()->constrained('laporans')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Dosen yang memberi komentar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['bimbingan_id']);
            $table->dropForeign(['laporan_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['bimbingan_id', 'laporan_id', 'user_id']);
            
            $table->unsignedBigInteger('commentable_id')->nullable();
            $table->string('commentable_type')->nullable();
        });
    }
};
