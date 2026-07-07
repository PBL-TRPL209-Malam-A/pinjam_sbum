<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PicController extends Controller
{
    // PIC Dashboard
    public function dashboard(Request $request)
    {
        $searchRoom = $request->query('search_room');
        $searchItem = $request->query('search_item');

        $ruangan = \App\Models\Ruangan::where('pic_id', auth()->id())
            ->when($searchRoom, function ($q) use ($searchRoom) {
                $q->where(function ($sub) use ($searchRoom) {
                    $sub->where('nama_ruangan', 'like', "%{$searchRoom}%")
                        ->orWhere('kode_ruangan', 'like', "%{$searchRoom}%");
                });
            })
            ->get();

        $barang = \App\Models\Barang::where('pic_id', auth()->id())
            ->when($searchItem, function ($q) use ($searchItem) {
                $q->where(function ($sub) use ($searchItem) {
                    $sub->where('nama_barang', 'like', "%{$searchItem}%")
                        ->orWhere('kode_barang', 'like', "%{$searchItem}%");
                });
            })
            ->get();

        $peminjamanDisetujui = Peminjaman::where('status', 'disetujui')->count();
        $kesiapanSelesai = 8; // Mocked
        $kendalaDilaporkan = 1; // Mocked

        return view('pic.dashboard', compact('ruangan', 'barang', 'peminjamanDisetujui', 'kesiapanSelesai', 'kendalaDilaporkan'));
    }

    // PIC Kesiapan index page
    public function kesiapanIndex()
    {
        $peminjaman = Peminjaman::with(['user', 'ruangan', 'barang', 'dosen'])
            ->where('status', 'menunggu_pic')
            ->where(function($q) {
                $q->whereHas('ruangan', function($sub) {
                    $sub->where('pic_id', auth()->id());
                })->orWhereHas('barang', function($sub) {
                    $sub->where('pic_id', auth()->id());
                });
            })
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
            'foto_kondisi' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);
        $status = $request->status_kesiapan === 'siap' ? 'menunggu_admin' : 'ditolak';

        $fotoPath = null;
        if ($request->hasFile('foto_kondisi')) {
            $file = $request->file('foto_kondisi');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/kesiapan'), $fileName);
            $fotoPath = 'uploads/kesiapan/' . $fileName;
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($peminjaman, $status, $request, $fotoPath) {
            $peminjaman->update([
                'status' => $status
            ]);

            \App\Models\VerifikasiPeminjaman::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'id_verifikator' => auth()->user()->id_user,
                'peran_verifikasi' => 'PIC Fasilitas',
                'jenis_verifikasi' => 'Pemeriksaan Kesiapan',
                'status' => $status === 'menunggu_admin' ? 'disetujui' : 'ditolak',
                'catatan' => $request->catatan ?? 'Pemeriksaan Kesiapan oleh PIC',
                'tanggal' => now(),
            ]);
        });

        return redirect()->route('pic.dashboard')->with('success', 'Status kesiapan fasilitas berhasil diperbarui.');
    }

    // PIC Pengembalian index page
    public function konfirmasiPengembalianIndex()
    {
        $peminjaman = Peminjaman::with([
                'user', 
                'ruangan', 
                'barang', 
                'pengembalianRuangan.detail', 
                'pengembalianBarang.detail'
            ])
            ->where(function($q) {
                $q->where(function($sub) {
                    $sub->where('jenis_peminjaman', 'ruangan')
                        ->whereHas('pengembalianRuangan', function($pr) {
                            $pr->where('status', 'menunggu_pic');
                        });
                })->orWhere(function($sub) {
                    $sub->where('jenis_peminjaman', 'barang')
                        ->whereHas('pengembalianBarang', function($pb) {
                            $pb->where('status', 'menunggu_pic');
                        });
                });
            })
            ->where(function($q) {
                $q->whereHas('ruangan', function($sub) {
                    $sub->where('pic_id', auth()->id());
                })->orWhereHas('barang', function($sub) {
                    $sub->where('pic_id', auth()->id());
                });
            })
            ->get();
        return view('pic.konfirmasi_pengembalian', compact('peminjaman'));
    }

    // PIC Pengembalian store action
    public function konfirmasiPengembalianStore(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|integer',
            'status_pengembalian' => 'required|in:selesai,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);
        $statusPengembalian = $request->status_pengembalian === 'selesai' ? 'menunggu_admin' : 'ditolak';

        \Illuminate\Support\Facades\DB::transaction(function () use ($peminjaman, $statusPengembalian, $request) {
            $id_pengembalian_ruangan = null;
            $id_pengembalian_barang = null;

            if ($peminjaman->jenis_peminjaman === 'ruangan') {
                $ret = $peminjaman->pengembalianRuangan;
                if ($ret) {
                    $ret->update(['status' => $statusPengembalian]);
                    $id_pengembalian_ruangan = $ret->id_pengembalian_ruangan;
                }
            } else {
                $ret = $peminjaman->pengembalianBarang;
                if ($ret) {
                    $ret->update(['status' => $statusPengembalian]);
                    $id_pengembalian_barang = $ret->id_pengembalian_barang;
                }
            }

            \App\Models\VerifikasiPengembalian::create([
                'id_pengembalian_ruangan' => $id_pengembalian_ruangan,
                'id_pengembalian_barang' => $id_pengembalian_barang,
                'id_verifikator' => auth()->user()->id_user,
                'peran_verifikasi' => 'PIC Fasilitas',
                'status' => $statusPengembalian === 'menunggu_admin' ? 'disetujui' : 'ditolak',
                'catatan' => $request->catatan ?? 'Verifikasi Pengembalian oleh PIC',
                'tanggal' => now(),
            ]);
        });

        return redirect()->route('pic.dashboard')->with('success', 'Status pengembalian fasilitas berhasil diperbarui.');
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
