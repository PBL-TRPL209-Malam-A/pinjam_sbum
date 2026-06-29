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
        $dosenId = auth()->id();
        $peminjaman = Peminjaman::with(['user', 'ruangan', 'barang'])
            ->where('dosen_id', $dosenId)
            ->where('status', 'menunggu_dosen')
            ->get();
            
        $menungguVerifikasi = Peminjaman::where('dosen_id', $dosenId)->where('status', 'menunggu_dosen')->count();
        $disetujuiHariIni = Peminjaman::where('dosen_id', $dosenId)->whereIn('status', ['menunggu_admin', 'menunggu_kepala', 'menunggu_pic', 'siap_digunakan'])->count();
        $ditolakRevisi = Peminjaman::where('dosen_id', $dosenId)->whereIn('status', ['ditolak', 'revisi'])->count();
        $kegiatanTerdekat = Peminjaman::where('dosen_id', $dosenId)->where('status', 'siap_digunakan')->count();

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
        $peminjaman = Peminjaman::with(['user', 'ruangan', 'barang'])
            ->where('dosen_id', auth()->id())
            ->where('status', 'menunggu_dosen')
            ->get();
        
        $menungguVerifikasi = Peminjaman::where('dosen_id', auth()->id())->where('status', 'menunggu_dosen')->count();
        $ditolakRevisi = Peminjaman::where('dosen_id', auth()->id())->where('status', 'revisi')->count();

        return view('dosen.verifikasi', compact('peminjaman', 'menungguVerifikasi', 'ditolakRevisi'));
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
            $status = 'menunggu_admin';
        } elseif ($status === 'revisi') {
            $status = 'revisi';
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($peminjaman, $status, $request) {
            $peminjaman->update([
                'status' => $status
            ]);

            // Insert log in verifikasi_peminjaman
            VerifikasiPeminjaman::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'id_verifikator' => auth()->user()->id_user,
                'peran_verifikasi' => 'Dosen',
                'jenis_verifikasi' => 'Persetujuan Akademik',
                'status' => $status === 'menunggu_admin' ? 'disetujui' : ($status === 'ditolak' ? 'ditolak' : 'pending'),
                'catatan' => $request->catatan ?? 'Diverifikasi oleh Dosen',
                'tanggal' => now(),
            ]);
        });

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
