<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class ValidasiController extends Controller
{
    public function cekPeminjaman($id)
    {
        // Temukan peminjaman beserta relasi yang diperlukan
        $peminjaman = Peminjaman::with(['user', 'ruangan', 'barang', 'dosen', 'verifikasi' => function($q) {
            $q->whereIn('peran_verifikasi', ['Kepala SBUM', 'Admin SBUM']);
        }])->findOrFail($id);

        // Cari verifikasi akhir (Kepala SBUM atau Bypass Admin)
        $verifikasiAkhir = $peminjaman->verifikasi->where('peran_verifikasi', 'Kepala SBUM')->first();
        
        $tanggalTerbit = $verifikasiAkhir ? $verifikasiAkhir->tanggal : $peminjaman->updated_at;

        return view('validasi.dokumen', compact('peminjaman', 'tanggalTerbit'));
    }
}
