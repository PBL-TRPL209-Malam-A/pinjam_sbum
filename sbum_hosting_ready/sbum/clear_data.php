<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    DB::table('verifikasi_pengembalian')->truncate();
    DB::table('verifikasi_peminjaman')->truncate();
    DB::table('detail_pengembalian_barang')->truncate();
    DB::table('pengembalian_barang')->truncate();
    DB::table('pengembalian_ruangan')->truncate();
    DB::table('detail_peminjaman_barang')->truncate();
    DB::table('detail_peminjaman_ruangan')->truncate();
    DB::table('peminjaman')->truncate();
    
    // Check if there are other relation tables
    // Like jadwal/schedules?
    DB::table('schedules')->truncate(); // if exists

    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    echo "Berhasil menghapus seluruh data peminjaman dan pengembalian.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
