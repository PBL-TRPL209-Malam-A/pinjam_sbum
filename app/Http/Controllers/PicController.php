<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PicController extends Controller
{
    // PIC Dashboard
    public function dashboard()
    {
        $ruangan = \App\Models\Ruangan::where('id_pic', auth()->id())->get();
        $peminjamanDisetujui = Peminjaman::where('status', 'disetujui')->count();
        $kesiapanSelesai = 8; // Mocked
        $kendalaDilaporkan = 1; // Mocked

        return view('pic.dashboard', compact('ruangan', 'peminjamanDisetujui', 'kesiapanSelesai', 'kendalaDilaporkan'));
    }

    // PIC Kesiapan index page
    public function kesiapanIndex()
    {
        $peminjaman = Peminjaman::with(['user', 'ruangan', 'barang'])
            ->where('status', 'disetujui')
            ->get();
        return view('pic.kesiapan', compact('peminjaman'));
    }

    // PIC Kesiapan store action
    public function kesiapanStore(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|integer',
            'status_kesiapan' => 'required|in:siap,kendala',
            'catatan' => 'nullable|string',
        ]);

        return redirect()->route('pic.dashboard')->with('success', 'Status kesiapan fasilitas berhasil diperbarui.');
    }

    // PIC Profil
    public function profilIndex()
    {
        $user = auth()->user();
        return view('pic.profil', compact('user'));
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
