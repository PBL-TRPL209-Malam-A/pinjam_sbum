<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengembalian_ruangan', function (Blueprint $table) {
            $table->string('foto_kondisi')->nullable();
            $table->string('dokumen_administrasi')->nullable();
        });
 
        Schema::table('pengembalian_barang', function (Blueprint $table) {
            $table->string('foto_kondisi')->nullable();
            $table->string('dokumen_administrasi')->nullable();
        });
    }
 
    public function down(): void
    {
        Schema::table('pengembalian_ruangan', function (Blueprint $table) {
            $table->dropColumn(['foto_kondisi', 'dokumen_administrasi']);
        });
 
        Schema::table('pengembalian_barang', function (Blueprint $table) {
            $table->dropColumn(['foto_kondisi', 'dokumen_administrasi']);
        });
    }
};
