<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PamdalController extends Controller
{
    // Pamdal Dashboard
    public function dashboard()
    {
        $todaySchedules = Peminjaman::with(['user', 'ruangan', 'barang'])
            ->where('status', 'disetujui')
            ->count();
        $totalPengawasan = 14; // Mocked
        $amanTerkendali = 12; // Mocked
        $adaKendala = 2; // Mocked

        return view('pamdal.dashboard', compact('todaySchedules', 'totalPengawasan', 'amanTerkendali', 'adaKendala'));
    }

    // Monitoring Hari Ini (Pengawasan)
    public function pengawasanIndex()
    {
        $peminjaman = Peminjaman::with(['user', 'ruangan', 'barang'])
            ->where('status', 'disetujui')
            ->get();
        return view('pamdal.pengawasan', compact('peminjaman'));
    }

    // Store monitoring report details
    public function pengawasanStore(Request $request)
    {
        $request->validate([
            'catatan' => 'required|string',
            'status_pengawasan' => 'required|string',
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
