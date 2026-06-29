<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
 
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->time('jam_mulai')->nullable()->after('tanggal_pengajuan');
            $table->time('jam_selesai')->nullable()->after('jam_mulai');
        });
 
        // Update existing records to default values
        DB::table('peminjaman')->update([
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '12:00:00',
        ]);
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['jam_mulai', 'jam_selesai']);
        });
    }
};
