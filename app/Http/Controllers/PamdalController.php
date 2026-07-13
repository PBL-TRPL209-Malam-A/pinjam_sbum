<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PamdalController extends Controller
{
    // Pamdal Dashboard
    public function dashboard()
    {
        $today = \Carbon\Carbon::today();
        
        $todaySchedules = Peminjaman::with(['user', 'ruangan', 'barang'])
            ->whereDate('tanggal_pengajuan', '>=', $today)
            ->whereIn('status', ['siap_digunakan', 'sedang_digunakan', 'selesai'])
            ->count();
            
        $totalPengawasan = \App\Models\VerifikasiPeminjaman::where('peran_verifikasi', 'Pamdal')->count();
        $amanTerkendali = \App\Models\VerifikasiPeminjaman::where('peran_verifikasi', 'Pamdal')
            ->where('status', 'disetujui')->count();
        $adaKendala = \App\Models\VerifikasiPeminjaman::where('peran_verifikasi', 'Pamdal')
            ->where('status', 'ditolak')->count();

        $jadwalKegiatan = Peminjaman::with(['user', 'ruangan', 'barang'])
            ->whereDate('tanggal_pengajuan', '>=', $today)
            ->whereIn('status', ['siap_digunakan', 'sedang_digunakan', 'selesai'])
            ->orderBy('tanggal_pengajuan', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        return view('pamdal.dashboard', compact('todaySchedules', 'totalPengawasan', 'amanTerkendali', 'adaKendala', 'jadwalKegiatan'));
    }

    // Monitoring Hari Ini (Pengawasan)
    public function pengawasanIndex()
    {
        $today = \Carbon\Carbon::today();
        $peminjaman = Peminjaman::with(['user', 'ruangan', 'barang'])
            ->whereDate('tanggal_pengajuan', $today)
            ->whereIn('status', ['siap_digunakan', 'sedang_digunakan', 'selesai'])
            ->get();
            
        return view('pamdal.pengawasan', compact('peminjaman'));
    }

    // Store monitoring report details
    public function pengawasanStore(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id_peminjaman',
            'catatan' => 'required|string',
            'status_pengawasan' => 'required|string',
        ]);

        $statusDb = $request->status_pengawasan === 'Aman Terkendali' ? 'disetujui' : 'ditolak';

        \App\Models\VerifikasiPeminjaman::create([
            'id_peminjaman' => $request->peminjaman_id,
            'id_verifikator' => auth()->id(),
            'peran_verifikasi' => 'Pamdal',
            'jenis_verifikasi' => 'Monitoring',
            'status' => $statusDb,
            'catatan' => $request->catatan,
            'tanggal' => now(),
        ]);

        return back()->with('success', 'Catatan pengawasan berhasil disimpan.');
    }

    // Pamdal Profil
    public function profilIndex()
    {
        $user = auth()->user();
        return view('pamdal.profil', compact('user'));
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
