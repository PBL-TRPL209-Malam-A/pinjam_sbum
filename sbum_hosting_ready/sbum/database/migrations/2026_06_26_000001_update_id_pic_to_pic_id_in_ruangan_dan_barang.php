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
        // 1. Drop constraints for ruangan
        try {
            Schema::table('ruangan', function (Blueprint $table) {
                $table->dropForeign('fk_ruangan_pic');
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('ruangan', function (Blueprint $table) {
                $table->dropForeign(['id_pic']);
            });
        } catch (\Exception $e) {}

        // Drop column and add new constraints for ruangan
        Schema::table('ruangan', function (Blueprint $table) {
            try {
                $table->dropColumn('id_pic');
            } catch (\Exception $e) {}
            
            $table->integer('pic_id')->nullable();
            $table->foreign('pic_id')->references('id_user')->on('user')->onDelete('restrict');
        });

        // 2. Drop constraints for barang
        try {
            Schema::table('barang', function (Blueprint $table) {
                $table->dropForeign('fk_barang_pic');
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('barang', function (Blueprint $table) {
                $table->dropForeign(['id_pic']);
            });
        } catch (\Exception $e) {}

        // Drop column and add new constraints for barang
        Schema::table('barang', function (Blueprint $table) {
            try {
                $table->dropColumn('id_pic');
            } catch (\Exception $e) {}
            
            $table->integer('pic_id')->nullable();
            $table->foreign('pic_id')->references('id_user')->on('user')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            try {
                $table->dropForeign(['pic_id']);
            } catch (\Exception $e) {}
            try {
                $table->dropColumn('pic_id');
            } catch (\Exception $e) {}
            $table->integer('id_pic')->nullable();
            $table->foreign('id_pic', 'fk_barang_pic')->references('id_user')->on('user')->onDelete('set null');
        });

        Schema::table('ruangan', function (Blueprint $table) {
            try {
                $table->dropForeign(['pic_id']);
            } catch (\Exception $e) {}
            try {
                $table->dropColumn('pic_id');
            } catch (\Exception $e) {}
            $table->integer('id_pic')->nullable();
            $table->foreign('id_pic', 'fk_ruangan_pic')->references('id_user')->on('user')->onDelete('set null');
        });
    }
};
