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
        Schema::table('pengembalian_ruangan', function (Blueprint $table) {
            $table->string('status')->default('menunggu_pic')->after('catatan');
        });

        Schema::table('pengembalian_barang', function (Blueprint $table) {
            $table->string('status')->default('menunggu_pic')->after('catatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengembalian_ruangan', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('pengembalian_barang', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
