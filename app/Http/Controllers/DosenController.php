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
        $disetujuiHariIni = VerifikasiPeminjaman::where('id_verifikator', $dosenId)->where('peran_verifikasi', 'Dosen')->whereIn('status', ['disetujui', 'menunggu_pic'])->whereDate('tanggal', today())->count();
        $ditolakRevisi = Peminjaman::where('dosen_id', $dosenId)->whereIn('status', ['ditolak', 'revisi'])->count();
        $kegiatanTerdekat = Peminjaman::where('dosen_id', $dosenId)->where('status', 'siap_digunakan')->count();

        // Data Asli untuk Riwayat Keputusan
        $riwayatKeputusan = VerifikasiPeminjaman::with('peminjaman.ruangan', 'peminjaman.barang')
            ->where('id_verifikator', auth()->user()->id_user)
            ->where('peran_verifikasi', 'Dosen')
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // Data Asli untuk Jadwal Kegiatan
        $jadwalKegiatan = Peminjaman::with('ruangan', 'barang', 'user')
            ->where('dosen_id', $dosenId)
            ->whereIn('status', ['menunggu_admin', 'menunggu_kepala', 'menunggu_pic', 'siap_digunakan'])
            ->where('tanggal_pengajuan', '>=', today())
            ->orderBy('tanggal_pengajuan', 'asc')
            ->take(3)
            ->get();

        return view('dosen.dashboard', compact(
            'peminjaman',
            'menungguVerifikasi',
            'disetujuiHariIni',
            'ditolakRevisi',
            'kegiatanTerdekat',
            'riwayatKeputusan',
            'jadwalKegiatan'
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

        if ($peminjaman->dosen_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak memverifikasi peminjaman ini.');
        }
        $request->validate([
            'status_pengajuan' => 'required|in:verif_dosen,ditolak,disetujui,pending,revisi',
            'catatan' => 'nullable|string',
        ]);

        $status = $request->status_pengajuan;
        if ($status === 'verif_dosen' || $status === 'disetujui') {
            $status = 'menunggu_pic';
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
                'status' => $status === 'menunggu_pic' ? 'disetujui' : ($status === 'ditolak' ? 'ditolak' : 'pending'),
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
