<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class KepalaSbumController extends Controller
{
    // F-017: Mengelola data staff SBUM
    public function staffIndex()
    {
        $staff = User::whereHas('roles', function($query) {
            $query->where('role_id', '!=', 1);
        })->with('roles')->get();

        $roles = \App\Models\Role::where('id_role', '!=', 1)->get();

        return view('kepalasbum.staff', compact('staff', 'roles'));
    }

    public function staffStore(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'nik' => 'required|string|max:20|unique:user,nik',
            'role_id' => 'required|exists:role,id_role',
            'email' => 'required|email|max:150|unique:user,email',
            'password' => 'required|string|min:6',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $user = User::create([
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nik,
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            ]);

            $user->roles()->attach($request->role_id);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('kepalasbum.staff')->with('success', 'Staf baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal menambahkan staf: ' . $e->getMessage());
        }
    }

    public function staffUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'nik' => 'required|string|max:20|unique:user,nik,' . $id . ',id_user',
            'role_id' => 'required|exists:role,id_role',
            'email' => 'required|email|max:150|unique:user,email,' . $id . ',id_user',
            'password' => 'nullable|string|min:6',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $updateData = [
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nik,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
            }

            $user->update($updateData);

            $user->roles()->sync([$request->role_id]);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('kepalasbum.staff')->with('success', 'Data staf berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal memperbarui staf: ' . $e->getMessage());
        }
    }

    public function staffDestroy($id)
    {
        $user = User::findOrFail($id);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Bersihkan relasi foreign key sebelum menghapus user
            \App\Models\VerifikasiPeminjaman::where('id_verifikator', $id)->delete();
            \App\Models\VerifikasiPengembalian::where('id_verifikator', $id)->delete();
            \App\Models\Peminjaman::where('dosen_id', $id)->update(['dosen_id' => null]);

            // Hapus peminjaman (dan relasinya) jika user ini yang mengajukan peminjaman
            $userPeminjamans = \App\Models\Peminjaman::where('user_id', $id)->get();
            foreach ($userPeminjamans as $p) {
                \App\Models\DetailPeminjamanRuangan::where('peminjaman_id', $p->id_peminjaman)->delete();
                \App\Models\DetailPeminjamanBarang::where('peminjaman_id', $p->id_peminjaman)->delete();
                
                $pr = \App\Models\PengembalianRuangan::where('peminjaman_id', $p->id_peminjaman)->first();
                if ($pr) {
                    \App\Models\DetailPengembalianRuangan::where('id_pengembalian_ruangan', $pr->id_pengembalian_ruangan)->delete();
                    $pr->delete();
                }
                
                $pb = \App\Models\PengembalianBarang::where('peminjaman_id', $p->id_peminjaman)->first();
                if ($pb) {
                    \App\Models\DetailPengembalianBarang::where('id_pengembalian_barang', $pb->id_pengembalian_barang)->delete();
                    $pb->delete();
                }
                
                $p->delete();
            }

            $user->roles()->detach();
            $user->delete();

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('kepalasbum.staff')->with('success', 'Staf berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal menghapus staf: ' . $e->getMessage());
        }
    }

    // F-018: Persetujuan akhir peminjaman
    public function persetujuanIndex()
    {
        $peminjaman = Peminjaman::with(['user', 'ruangan.pic', 'barang.pic', 'dosen', 'verifikasi.verifikator'])
            ->where('status', 'menunggu_kepala')
            ->get();
        return view('kepalasbum.persetujuan', compact('peminjaman'));
    }

    public function verifikasi(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        $request->validate([
            'status_pengajuan' => 'required|in:disetujui_kepala,ditolak,disetujui,pending',
        ]);

        $status = $request->status_pengajuan;
        if ($status === 'disetujui_kepala' || $status === 'disetujui') {
            $status = 'menunggu_pic';
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($peminjaman, $status, $request) {
            $peminjaman->update([
                'status' => $status
            ]);

            // Insert log in verifikasi_peminjaman
            \App\Models\VerifikasiPeminjaman::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'id_verifikator' => auth()->user()->id_user,
                'peran_verifikasi' => 'Kepala SBUM',
                'jenis_verifikasi' => 'Persetujuan Akhir',
                'status' => $status === 'menunggu_pic' ? 'disetujui' : 'ditolak',
                'catatan' => $request->catatan ?? 'Diverifikasi oleh Kepala SBUM',
                'tanggal' => now(),
            ]);
        });

        return back()->with('success', 'Persetujuan akhir berhasil disimpan');
    }

    // F-019, F-020: Laporan
    public function laporanIndex()
    {
        $peminjaman = Peminjaman::with(['user', 'ruangan', 'barang'])->get();
        
        $ruangan = \App\Models\PengembalianRuangan::with(['peminjaman.user', 'peminjaman.ruangan'])->get();
        $barang = \App\Models\PengembalianBarang::with(['peminjaman.user', 'peminjaman.barang'])->get();

        $pengembalian = collect();

        foreach ($ruangan as $r) {
            $pengembalian->push((object)[
                'id_pengembalian' => $r->id_pengembalian_ruangan,
                'peminjaman' => $r->peminjaman,
                'tanggal_kembali' => $r->tanggal_pengembalian,
                'kondisi_kembali' => $r->detail->first()->kondisi_ruangan ?? 'baik',
                'status_pengembalian' => $r->peminjaman->status === 'selesai' ? 'dikonfirmasi_admin' : 'pending',
            ]);
        }

        foreach ($barang as $b) {
            $pengembalian->push((object)[
                'id_pengembalian' => $b->id_pengembalian_barang,
                'peminjaman' => $b->peminjaman,
                'tanggal_kembali' => $b->tanggal,
                'kondisi_kembali' => $b->detail->first()->kondisi_barang ?? 'baik',
                'status_pengembalian' => $b->peminjaman->status === 'selesai' ? 'dikonfirmasi_admin' : 'pending',
            ]);
        }

        return view('kepalasbum.laporan', compact('peminjaman', 'pengembalian'));
    }

    public function laporanPengembalian()
    {
        $ruangan = \App\Models\PengembalianRuangan::with(['peminjaman.user', 'peminjaman.ruangan'])->get();
        $barang = \App\Models\PengembalianBarang::with(['peminjaman.user', 'peminjaman.barang'])->get();

        $pengembalian = collect();

        foreach ($ruangan as $r) {
            $pengembalian->push((object)[
                'id_pengembalian' => $r->id_pengembalian_ruangan,
                'peminjaman' => $r->peminjaman,
                'tanggal_kembali' => $r->tanggal_pengembalian,
                'kondisi_kembali' => $r->detail->first()->kondisi_ruangan ?? 'baik',
                'status_pengembalian' => $r->peminjaman->status === 'selesai' ? 'dikonfirmasi_admin' : 'pending',
            ]);
        }

        foreach ($barang as $b) {
            $pengembalian->push((object)[
                'id_pengembalian' => $b->id_pengembalian_barang,
                'peminjaman' => $b->peminjaman,
                'tanggal_kembali' => $b->tanggal,
                'kondisi_kembali' => $b->detail->first()->kondisi_barang ?? 'baik',
                'status_pengembalian' => $b->peminjaman->status === 'selesai' ? 'dikonfirmasi_admin' : 'pending',
            ]);
        }

        return view('kepalasbum.laporan_pengembalian', compact('pengembalian'));
    }

    public function dashboard()
    {
        $totalStaff = User::whereHas('roles', function($query) {
            $query->where('role_id', '!=', 1);
        })->count();

        $totalPeminjaman = Peminjaman::count();
        $pendingPersetujuan = Peminjaman::where('status', 'menunggu_kepala')->count();

        return view('kepalasbum.dashboard', compact('totalStaff', 'totalPeminjaman', 'pendingPersetujuan'));
    }

    public function profil()
    {
        return view('kepalasbum.profil');
    }
}
