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
        Schema::create('verifikasi_peminjaman', function (Blueprint $table) {
            $table->integer('id_verifikasi_peminjaman')->autoIncrement();
            $table->integer('id_peminjaman');
            $table->integer('id_verifikator');
            $table->string('peran_verifikasi', 100)->nullable();
            $table->string('jenis_verifikasi', 100)->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal')->useCurrent();

            $table->foreign('id_peminjaman')->references('id_peminjaman')->on('peminjaman')->onDelete('cascade');
            $table->foreign('id_verifikator')->references('id_user')->on('user');
        });

        Schema::create('verifikasi_pengembalian', function (Blueprint $table) {
            $table->integer('id_verifikasi_pengembalian')->autoIncrement();
            $table->integer('id_pengembalian_ruangan')->nullable();
            $table->integer('id_pengembalian_barang')->nullable();
            $table->integer('id_verifikator');
            $table->string('peran_verifikasi', 100)->nullable();
            $table->timestamp('tanggal')->useCurrent();
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan')->nullable();

            $table->foreign('id_pengembalian_ruangan')->references('id_pengembalian_ruangan')->on('pengembalian_ruangan');
            $table->foreign('id_pengembalian_barang')->references('id_pengembalian_barang')->on('pengembalian_barang');
            $table->foreign('id_verifikator')->references('id_user')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi_pengembalian');
        Schema::dropIfExists('verifikasi_peminjaman');
    }
};
