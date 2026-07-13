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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('ruangan_id');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status', ['tersedia', 'dipinjam', 'pending'])->default('tersedia');
            $table->integer('peminjaman_id')->nullable();

            $table->foreign('ruangan_id')->references('id_ruangan')->on('ruangan')->onDelete('cascade');
            $table->foreign('peminjaman_id')->references('id_peminjaman')->on('peminjaman')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
