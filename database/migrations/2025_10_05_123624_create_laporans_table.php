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
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 150)->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir')->nullable();
            $table->text('deskripsi')->nullable();

            // 🔗 relasi ke mahasiswa (user dengan role "mahasiswa")
            $table->unsignedBigInteger('mahasiswa_id')->nullable();

            // 🔗 relasi ke dosen pembimbing (user dengan role "dosen")
            $table->unsignedBigInteger('dosen_id')->nullable();

            $table->string('dokumen', 255)->nullable();
            $table->string('status', 50)->nullable()->default('pending');
            $table->enum('type', ['proposal', 'magang', 'skripsi'])->nullable();
            $table->timestamps();

            // 🧩 Foreign keys
            $table->foreign('mahasiswa_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('dosen_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
