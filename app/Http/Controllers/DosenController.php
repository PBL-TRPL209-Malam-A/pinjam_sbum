<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\VerifikasiPeminjaman;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    // Dashboard Dosen
    public function dashboard()
    {
        $peminjaman = Peminjaman::with(['user', 'ruangan', 'barang'])
            ->where('status', 'pending')
            ->get();
            
        $menungguVerifikasi = Peminjaman::where('status', 'pending')->count();
        $disetujuiHariIni = 3; // Mocked
        $ditolakRevisi = 2; // Mocked
        $kegiatanTerdekat = 4; // Mocked

        return view('dosen.dashboard', compact(
            'peminjaman',
            'menungguVerifikasi',
            'disetujuiHariIni',
            'ditolakRevisi',
            'kegiatanTerdekat'
        ));
    }

    // Verifikasi Peminjaman index page
    public function verifikasiPeminjamanIndex()
    {
        $peminjaman = Peminjaman::with(['user', 'ruangan', 'barang'])->get();
        return view('dosen.verifikasi', compact('peminjaman'));
    }

    // Process Dosen verification decision
    public function verifikasi(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        $request->validate([
            'status_pengajuan' => 'required|in:verif_dosen,ditolak,disetujui,pending,revisi',
            'catatan' => 'nullable|string',
        ]);

        $status = $request->status_pengajuan;
        if ($status === 'verif_dosen' || $status === 'disetujui') {
            $status = 'disetujui';
        } elseif ($status === 'revisi') {
            $status = 'pending';
        }

        $peminjaman->update([
            'status' => $status
        ]);

        // Insert log in verifikasi_peminjaman
        VerifikasiPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_verifikator' => auth()->user()->id_user,
            'peran_verifikasi' => 'Dosen',
            'jenis_verifikasi' => 'Persetujuan Akademik',
            'status' => $status === 'disetujui' ? 'disetujui' : ($status === 'ditolak' ? 'ditolak' : 'pending'),
            'catatan' => $request->catatan ?? 'Diverifikasi oleh Dosen',
            'tanggal' => now(),
        ]);

        return redirect()->route('dosen.verifikasi-peminjaman')->with('success', 'Keputusan verifikasi berhasil disimpan.');
    }

    // Profile Dosen
    public function profilIndex()
    {
        $user = auth()->user();
        return view('dosen.profil', compact('user'));
    }

    public function profilUpdate(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:user,email,' . $user->id_user . ',id_user',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->nama_lengkap = $request->nama_lengkap;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
