<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks for seeding
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // 1. Seed Role Table
        DB::table('role')->truncate();
        DB::table('role')->insert([
            ['id_role' => 1, 'nama_role' => 'Mahasiswa'],
            ['id_role' => 2, 'nama_role' => 'Dosen'],
            ['id_role' => 3, 'nama_role' => 'Admin SBUM'],
            ['id_role' => 4, 'nama_role' => 'Kepala SBUM'],
            ['id_role' => 5, 'nama_role' => 'PIC Fasilitas'],
            ['id_role' => 6, 'nama_role' => 'Pamdal'],
        ]);

        // 2. Seed User Table
        DB::table('user')->truncate();
        DB::table('user')->insert([
            [
                'id_user' => 1,
                'nama_lengkap' => 'Kepala SBUM',
                'nim' => 'K001',
                'nik' => null,
                'email' => 'kepala@sbum.ac.id',
                'password' => Hash::make('hash_kepala'),
                'created_at' => '2026-04-21 20:15:16',
            ]
        ]);

        // 3. Seed User Role Table
        DB::table('user_role')->truncate();
        DB::table('user_role')->insert([
            ['id_user_role' => 1, 'user_id' => 1, 'role_id' => 4],
            
        ]);

        // 4. Seed Ruangan Table
        DB::table('ruangan')->truncate();

        // 5. Seed Barang Table
        DB::table('barang')->truncate();
    

        // 6. Seed Fasilitas Ruangan Table
        DB::table('fasilitas_ruangan')->truncate();
        // 7. Seed Peminjaman Table
        DB::table('peminjaman')->truncate();
        

        // 8. Seed Detail Peminjaman Ruangan
        DB::table('detail_peminjaman_ruangan')->truncate();
        
        // 9. Seed Detail Peminjaman Barang
        DB::table('detail_peminjaman_barang')->truncate();

        // 10. Seed Verifikasi Peminjaman
        DB::table('verifikasi_peminjaman')->truncate();

        // Truncate remaining tables to clean state
        DB::table('pengembalian_ruangan')->truncate();
        DB::table('detail_pengembalian_ruangan')->truncate();
        DB::table('pengembalian_barang')->truncate();
        DB::table('detail_pengembalian_barang')->truncate();
        DB::table('verifikasi_pengembalian')->truncate();

        // Enable foreign key checks back
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
