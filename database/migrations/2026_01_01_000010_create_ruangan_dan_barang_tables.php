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
        Schema::create('ruangan', function (Blueprint $table) {
            $table->integer('id_ruangan')->autoIncrement();
            $table->string('nama_ruangan', 150);
            $table->string('nama_gedung', 150)->nullable();
            $table->string('kode_ruangan', 50)->nullable()->unique();
            $table->integer('kapasitas')->nullable();
            $table->string('lantai', 20)->nullable();
            $table->enum('status_ruangan', ['tersedia', 'tidak tersedia', 'maintenance'])->default('tersedia');
            $table->string('foto_ruangan', 255)->nullable();
            $table->integer('id_pic')->nullable();

            $table->foreign('id_pic')->references('id_user')->on('user')->onDelete('set null');
        });

        Schema::create('barang', function (Blueprint $table) {
            $table->integer('id_barang')->autoIncrement();
            $table->string('nama_barang', 150);
            $table->string('kode_barang', 50)->nullable()->unique();
            $table->integer('stok_tersedia')->default(0);
            $table->string('foto_barang', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('id_pic')->nullable();

            $table->foreign('id_pic')->references('id_user')->on('user')->onDelete('set null');
        });

        Schema::create('fasilitas_ruangan', function (Blueprint $table) {
            $table->integer('id_fasilitas')->autoIncrement();
            $table->integer('id_ruangan');
            $table->string('nama_fasilitas', 150);
            $table->integer('jumlah')->default(1);
            $table->text('keterangan')->nullable();

            $table->foreign('id_ruangan')->references('id_ruangan')->on('ruangan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas_ruangan');
        Schema::dropIfExists('barang');
        Schema::dropIfExists('ruangan');
    }
};
