<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengembalian_ruangan', function (Blueprint $table) {
            $table->dropColumn('tanggal_selesai_aktual');
        });

        Schema::table('pengembalian_barang', function (Blueprint $table) {
            $table->dropColumn('tanggal_selesai_aktual');
        });
    }

    public function down(): void
    {
        Schema::table('pengembalian_ruangan', function (Blueprint $table) {
            $table->date('tanggal_selesai_aktual')->nullable();
        });

        Schema::table('pengembalian_barang', function (Blueprint $table) {
            $table->date('tanggal_selesai_aktual')->nullable();
        });
    }
};
