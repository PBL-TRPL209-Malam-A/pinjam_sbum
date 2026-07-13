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
        Schema::create('pengembalian_ruangan', function (Blueprint $table) {
            $table->integer('id_pengembalian_ruangan')->autoIncrement();
            $table->integer('peminjaman_id');
            $table->date('tanggal_pengembalian')->nullable();
            $table->text('catatan')->nullable();

            $table->foreign('peminjaman_id')->references('id_peminjaman')->on('peminjaman');
        });

        Schema::create('detail_pengembalian_ruangan', function (Blueprint $table) {
            $table->integer('id_detail_pengembalian_ruangan')->autoIncrement();
            $table->integer('id_pengembalian_ruangan');
            $table->integer('id_ruangan');
            $table->enum('kondisi_ruangan', ['baik', 'rusak ringan', 'rusak berat'])->nullable();
            $table->text('catatan')->nullable();

            $table->foreign('id_pengembalian_ruangan')->references('id_pengembalian_ruangan')->on('pengembalian_ruangan')->onDelete('cascade');
            $table->foreign('id_ruangan')->references('id_ruangan')->on('ruangan');
        });

        Schema::create('pengembalian_barang', function (Blueprint $table) {
            $table->integer('id_pengembalian_barang')->autoIncrement();
            $table->integer('peminjaman_id');
            $table->date('tanggal')->nullable();
            $table->text('catatan')->nullable();

            $table->foreign('peminjaman_id')->references('id_peminjaman')->on('peminjaman');
        });

        Schema::create('detail_pengembalian_barang', function (Blueprint $table) {
            $table->integer('id_detail_pengembalian_barang')->autoIncrement();
            $table->integer('id_pengembalian_barang');
            $table->integer('id_barang');
            $table->integer('jumlah_barang_dikembalikan')->default(1);
            $table->enum('kondisi_barang', ['baik', 'rusak ringan', 'rusak berat', 'hilang'])->nullable();
            $table->text('catatan')->nullable();

            $table->foreign('id_pengembalian_barang')->references('id_pengembalian_barang')->on('pengembalian_barang')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengembalian_barang');
        Schema::dropIfExists('pengembalian_barang');
        Schema::dropIfExists('detail_pengembalian_ruangan');
        Schema::dropIfExists('pengembalian_ruangan');
    }
};
