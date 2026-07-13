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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->integer('id_peminjaman')->autoIncrement();
            $table->integer('user_id');
            $table->integer('dosen_id')->nullable();
            $table->string('nama_kegiatan', 200);
            $table->integer('jumlah_peserta')->nullable();
            $table->enum('jenis_peminjaman', ['barang', 'ruangan', 'keduanya']);
            $table->timestamp('tanggal_pengajuan')->useCurrent();
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'selesai', 'dibatalkan'])->default('pending');
            $table->text('keterangan')->nullable();

            $table->foreign('user_id')->references('id_user')->on('user');
            $table->foreign('dosen_id')->references('id_user')->on('user');
        });

        Schema::create('detail_peminjaman_ruangan', function (Blueprint $table) {
            $table->integer('id_detail_peminjaman_ruangan')->autoIncrement();
            $table->integer('peminjaman_id');
            $table->integer('ruangan_id');

            $table->foreign('peminjaman_id')->references('id_peminjaman')->on('peminjaman')->onDelete('cascade');
            $table->foreign('ruangan_id')->references('id_ruangan')->on('ruangan');
        });

        Schema::create('detail_peminjaman_barang', function (Blueprint $table) {
            $table->integer('id_detail_peminjaman_barang')->autoIncrement();
            $table->integer('peminjaman_id');
            $table->integer('barang_id');
            $table->integer('jumlah')->default(1);

            $table->foreign('peminjaman_id')->references('id_peminjaman')->on('peminjaman')->onDelete('cascade');
            $table->foreign('barang_id')->references('id_barang')->on('barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman_barang');
        Schema::dropIfExists('detail_peminjaman_ruangan');
        Schema::dropIfExists('peminjaman');
    }
};
