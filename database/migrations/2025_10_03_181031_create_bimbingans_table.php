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
        Schema::create('bimbingans', function (Blueprint $table) {
            $table->id();
            $table->string('topik', 50)->nullable();
            $table->string('status', 50)->nullable();
            $table->unsignedBigInteger('user_id'); // mahasiswa
            $table->unsignedBigInteger('dosen_id')->nullable(); // dosen
            $table->date('tanggal')->nullable();
            $table->string('isi', 255)->nullable();
            $table->string('type', 50);
            $table->string('komentar', 100)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('dosen_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimbingans');
    }
};
