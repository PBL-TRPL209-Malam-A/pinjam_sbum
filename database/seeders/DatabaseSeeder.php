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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

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
                'nama_lengkap' => 'Moch Azmi Aris Sandita',
                'nim' => '4342511024',
                'nik' => null,
                'email' => 'azmi@sbum.ac.id',
                'password' => Hash::make('hash_azmi'),
                'created_at' => '2026-04-21 20:15:16',
            ],
            [
                'id_user' => 2,
                'nama_lengkap' => 'Dosen Penanggung Jawab',
                'nim' => 'D001',
                'nik' => null,
                'email' => 'dosen@sbum.ac.id',
                'password' => Hash::make('hash_dosen'),
                'created_at' => '2026-04-21 20:15:16',
            ],
            [
                'id_user' => 3,
                'nama_lengkap' => 'Admin SBUM',
                'nim' => 'A001',
                'nik' => null,
                'email' => 'admin@sbum.ac.id',
                'password' => Hash::make('hash_admin'),
                'created_at' => '2026-04-21 20:15:16',
            ],
            [
                'id_user' => 4,
                'nama_lengkap' => 'Kepala SBUM',
                'nim' => 'K001',
                'nik' => null,
                'email' => 'kepala@sbum.ac.id',
                'password' => Hash::make('hash_kepala'),
                'created_at' => '2026-04-21 20:15:16',
            ],
            [
                'id_user' => 5,
                'nama_lengkap' => 'PIC Ruangan',
                'nim' => 'P001',
                'nik' => null,
                'email' => 'pic@sbum.ac.id',
                'password' => Hash::make('hash_pic'),
                'created_at' => '2026-04-21 20:15:16',
            ],
            [
                'id_user' => 6,
                'nama_lengkap' => 'Petugas Pamdal',
                'nim' => 'PM001',
                'nik' => null,
                'email' => 'pamdal@sbum.ac.id',
                'password' => Hash::make('hash_pamdal'),
                'created_at' => '2026-04-21 20:15:16',
            ],
            [
                'id_user' => 13,
                'nama_lengkap' => 'sihab',
                'nim' => '4342511099',
                'nik' => null,
                'email' => 'ketua.pbl@student.polibatam.ac.id',
                'password' => Hash::make('hash_pbl_123'),
                'created_at' => '2026-04-21 21:24:06',
            ],
        ]);

        // 3. Seed User Role Table
        DB::table('user_role')->truncate();
        DB::table('user_role')->insert([
            ['id_user_role' => 1, 'user_id' => 1, 'role_id' => 1],
            ['id_user_role' => 2, 'user_id' => 2, 'role_id' => 2],
            ['id_user_role' => 3, 'user_id' => 3, 'role_id' => 3],
            ['id_user_role' => 4, 'user_id' => 4, 'role_id' => 4],
            ['id_user_role' => 5, 'user_id' => 5, 'role_id' => 5],
            ['id_user_role' => 6, 'user_id' => 6, 'role_id' => 6],
            ['id_user_role' => 7, 'user_id' => 13, 'role_id' => 1],
        ]);

        // 4. Seed Ruangan Table
        DB::table('ruangan')->truncate();
        DB::table('ruangan')->insert([
            [
                'id_ruangan' => 1,
                'nama_ruangan' => 'Ruang Seminar 1',
                'nama_gedung' => 'Gedung Utama',
                'kode_ruangan' => 'R101',
                'kapasitas' => 100,
                'lantai' => '1',
                'status_ruangan' => 'tersedia',
                'foto_ruangan' => null,
                'id_pic' => 5,
            ],
            [
                'id_ruangan' => 2,
                'nama_ruangan' => 'Ruang Rapat 2',
                'nama_gedung' => 'Gedung Utama',
                'kode_ruangan' => 'R102',
                'kapasitas' => 40,
                'lantai' => '1',
                'status_ruangan' => 'tersedia',
                'foto_ruangan' => null,
                'id_pic' => 5,
            ],
        ]);

        // 5. Seed Barang Table
        DB::table('barang')->truncate();
        DB::table('barang')->insert([
            [
                'id_barang' => 1,
                'nama_barang' => 'Proyektor',
                'kode_barang' => 'BRG001',
                'stok_tersedia' => 5,
                'foto_barang' => null,
                'keterangan' => 'Proyektor portable',
                'id_pic' => 5,
            ],
            [
                'id_barang' => 2,
                'nama_barang' => 'Microphone',
                'kode_barang' => 'BRG002',
                'stok_tersedia' => 10,
                'foto_barang' => null,
                'keterangan' => 'Microphone wireless',
                'id_pic' => 5,
            ],
        ]);

        // 6. Seed Fasilitas Ruangan Table
        DB::table('fasilitas_ruangan')->truncate();
        DB::table('fasilitas_ruangan')->insert([
            [
                'id_fasilitas' => 1,
                'id_ruangan' => 1,
                'nama_fasilitas' => 'AC',
                'jumlah' => 2,
                'keterangan' => 'AC ruangan',
            ],
            [
                'id_fasilitas' => 2,
                'id_ruangan' => 1,
                'nama_fasilitas' => 'Proyektor Tetap',
                'jumlah' => 1,
                'keterangan' => 'Terpasang di plafon',
            ],
        ]);

        // 7. Seed Peminjaman Table
        DB::table('peminjaman')->truncate();
        DB::table('peminjaman')->insert([
            [
                'id_peminjaman' => 1,
                'user_id' => 1,
                'dosen_id' => 2,
                'nama_kegiatan' => 'Seminar Database',
                'jumlah_peserta' => 80,
                'jenis_peminjaman' => 'keduanya',
                'tanggal_pengajuan' => '2026-04-21 20:15:16',
                'status' => 'pending',
                'keterangan' => 'Kegiatan seminar basis data',
            ],
            [
                'id_peminjaman' => 2,
                'user_id' => 13,
                'dosen_id' => 2,
                'nama_kegiatan' => 'Rapat Koordinasi PBL SBUM TRPL 2A Malam',
                'jumlah_peserta' => 6,
                'jenis_peminjaman' => 'ruangan',
                'tanggal_pengajuan' => '2026-04-21 21:27:01',
                'status' => 'pending',
                'keterangan' => 'Rapat pembahasan ERD dan Normalisasi Database',
            ],
        ]);

        // 8. Seed Detail Peminjaman Ruangan
        DB::table('detail_peminjaman_ruangan')->truncate();
        DB::table('detail_peminjaman_ruangan')->insert([
            ['id_detail_peminjaman_ruangan' => 1, 'peminjaman_id' => 1, 'ruangan_id' => 1],
            ['id_detail_peminjaman_ruangan' => 2, 'peminjaman_id' => 2, 'ruangan_id' => 2],
        ]);

        // 9. Seed Detail Peminjaman Barang
        DB::table('detail_peminjaman_barang')->truncate();
        DB::table('detail_peminjaman_barang')->insert([
            ['id_detail_peminjaman_barang' => 1, 'peminjaman_id' => 1, 'barang_id' => 1, 'jumlah' => 1],
            ['id_detail_peminjaman_barang' => 2, 'peminjaman_id' => 1, 'barang_id' => 2, 'jumlah' => 2],
        ]);

        // 10. Seed Verifikasi Peminjaman
        DB::table('verifikasi_peminjaman')->truncate();
        DB::table('verifikasi_peminjaman')->insert([
            [
                'id_verifikasi_peminjaman' => 1,
                'id_peminjaman' => 1,
                'id_verifikator' => 2,
                'peran_verifikasi' => 'Dosen',
                'jenis_verifikasi' => 'Persetujuan Akademik',
                'status' => 'disetujui',
                'catatan' => 'Layak dilaksanakan',
                'tanggal' => '2026-06-08 00:00:00',
            ],
            [
                'id_verifikasi_peminjaman' => 2,
                'id_peminjaman' => 2,
                'id_verifikator' => 2,
                'peran_verifikasi' => 'Dosen',
                'jenis_verifikasi' => 'Persetujuan Akademik',
                'status' => 'disetujui',
                'catatan' => 'Silakan gunakan ruangan dengan baik, jaga kebersihan.',
                'tanggal' => '2026-05-10 10:00:00',
            ],
            [
                'id_verifikasi_peminjaman' => 3,
                'id_peminjaman' => 2,
                'id_verifikator' => 3,
                'peran_verifikasi' => 'Admin SBUM',
                'jenis_verifikasi' => 'Verifikasi Operasional',
                'status' => 'pending',
                'catatan' => 'Jadwal sedang dicek ulang dengan logistik.',
                'tanggal' => '2026-05-10 10:15:00',
            ],
        ]);

        // Truncate remaining tables to clean state
        DB::table('pengembalian_ruangan')->truncate();
        DB::table('detail_pengembalian_ruangan')->truncate();
        DB::table('pengembalian_barang')->truncate();
        DB::table('detail_pengembalian_barang')->truncate();
        DB::table('verifikasi_pengembalian')->truncate();

        // Enable foreign key checks back
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
