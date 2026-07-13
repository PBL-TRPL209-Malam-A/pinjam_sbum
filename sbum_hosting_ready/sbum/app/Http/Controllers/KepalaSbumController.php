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

    public function verifikasiSemua(\Illuminate\Http\Request $request)
    {
        $peminjamans = Peminjaman::where('status', 'menunggu_kepala')->get();

        if ($peminjamans->isEmpty()) {
            return back()->with('success', 'Tidak ada pengajuan yang perlu disetujui.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($peminjamans) {
            foreach ($peminjamans as $peminjaman) {
                $peminjaman->update([
                    'status' => 'siap_digunakan'
                ]);

                \App\Models\VerifikasiPeminjaman::create([
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'id_verifikator' => auth()->user()->id_user,
                    'peran_verifikasi' => 'Kepala SBUM',
                    'jenis_verifikasi' => 'Persetujuan Akhir',
                    'status' => 'disetujui',
                    'catatan' => 'Disetujui massal oleh Kepala SBUM',
                    'tanggal' => now(),
                ]);
            }
        });

        return back()->with('success', 'Semua pengajuan peminjaman berhasil disetujui.');
    }

    public function verifikasi(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        $request->validate([
            'status_pengajuan' => 'required|in:disetujui_kepala,ditolak,disetujui,pending',
        ]);

        $status = $request->status_pengajuan;
        if ($status === 'disetujui_kepala' || $status === 'disetujui') {
            $status = 'siap_digunakan';
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
                'status' => $status === 'siap_digunakan' ? 'disetujui' : 'ditolak',
                'catatan' => $request->catatan ?? 'Diverifikasi oleh Kepala SBUM',
                'tanggal' => now(),
            ]);
        });

        return back()->with('success', 'Persetujuan akhir berhasil disimpan');
    }

    // F-019, F-020: Laporan
    public function laporanIndex(\Illuminate\Http\Request $request)
    {
        $query = Peminjaman::with(['user', 'ruangan', 'barang']);

        if ($request->filled('periode')) {
            $yearMonth = explode('-', $request->periode);
            if (count($yearMonth) == 2) {
                $query->whereYear('tanggal_pengajuan', $yearMonth[0])
                      ->whereMonth('tanggal_pengajuan', $yearMonth[1]);
            }
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_peminjaman', $request->jenis);
        }

        $peminjaman = $query->get();
        
        $ruanganQuery = \App\Models\PengembalianRuangan::with(['peminjaman.user', 'peminjaman.ruangan']);
        $barangQuery = \App\Models\PengembalianBarang::with(['peminjaman.user', 'peminjaman.barang']);

        if ($request->filled('periode')) {
            $yearMonth = explode('-', $request->periode);
            if (count($yearMonth) == 2) {
                $ruanganQuery->whereYear('tanggal_pengembalian', $yearMonth[0])
                             ->whereMonth('tanggal_pengembalian', $yearMonth[1]);
                $barangQuery->whereYear('tanggal', $yearMonth[0])
                            ->whereMonth('tanggal', $yearMonth[1]);
            }
        }

        if ($request->filled('jenis')) {
            if ($request->jenis === 'barang') {
                $ruanganQuery->whereRaw('1 = 0'); // Return empty for ruangan if jenis is barang
            } elseif ($request->jenis === 'ruangan') {
                $barangQuery->whereRaw('1 = 0'); // Return empty for barang if jenis is ruangan
            }
        }

        $ruangan = $ruanganQuery->get();
        $barang = $barangQuery->get();

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

        // Statistik Laporan
        $totalPeminjaman = $peminjaman->count();
        $disetujui = $peminjaman->whereIn('status', ['menunggu_pic', 'siap_digunakan', 'sedang_digunakan', 'selesai'])->count();
        $ditolak = $peminjaman->where('status', 'ditolak')->count();

        $fasilitasCount = [];
        foreach ($peminjaman as $p) {
            if ($p->ruangan->isNotEmpty()) {
                $nama = $p->ruangan->first()->nama_ruangan;
                $fasilitasCount[$nama] = ($fasilitasCount[$nama] ?? 0) + 1;
            }
            if ($p->barang->isNotEmpty()) {
                $nama = $p->barang->first()->nama_barang;
                $fasilitasCount[$nama] = ($fasilitasCount[$nama] ?? 0) + 1;
            }
        }

        arsort($fasilitasCount);
        $ruangTerbanyak = key($fasilitasCount) ?: 'Belum Ada';
        $ruangTerbanyakCount = current($fasilitasCount) ?: 0;
        
        $ringkasanFasilitas = array_slice($fasilitasCount, 0, 10, true);

        return view('kepalasbum.laporan', compact(
            'peminjaman', 'pengembalian', 
            'totalPeminjaman', 'disetujui', 'ditolak', 
            'ruangTerbanyak', 'ruangTerbanyakCount', 
            'ringkasanFasilitas'
        ));
    }

    public function laporanPengembalian(\Illuminate\Http\Request $request)
    {
        $ruanganQuery = \App\Models\PengembalianRuangan::with(['peminjaman.user', 'peminjaman.ruangan']);
        $barangQuery = \App\Models\PengembalianBarang::with(['peminjaman.user', 'peminjaman.barang']);

        if ($request->filled('periode')) {
            $yearMonth = explode('-', $request->periode);
            if (count($yearMonth) == 2) {
                $ruanganQuery->whereYear('tanggal_pengembalian', $yearMonth[0])
                             ->whereMonth('tanggal_pengembalian', $yearMonth[1]);
                $barangQuery->whereYear('tanggal', $yearMonth[0])
                            ->whereMonth('tanggal', $yearMonth[1]);
            }
        }

        if ($request->filled('jenis')) {
            if ($request->jenis === 'barang') {
                $ruanganQuery->whereRaw('1 = 0');
            } elseif ($request->jenis === 'ruangan') {
                $barangQuery->whereRaw('1 = 0');
            }
        }

        $ruangan = $ruanganQuery->get();
        $barang = $barangQuery->get();

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

        $totalPengembalian = $pengembalian->count();
        $kondisiBaik = 0;
        $tindakLanjut = 0;
        $terlambat = 0;

        foreach ($pengembalian as $p) {
            $kondisi = strtolower($p->kondisi_kembali);
            if ($kondisi == 'baik') {
                $kondisiBaik++;
            } elseif (in_array($kondisi, ['hilang', 'rusak', 'terlambat'])) {
                $terlambat++;
            } else {
                $tindakLanjut++;
            }
        }
 
        return view('kepalasbum.laporan_pengembalian', compact(
            'pengembalian', 'totalPengembalian', 'kondisiBaik', 'tindakLanjut', 'terlambat'
        ));
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

    public function exportPeminjamanExcel(\Illuminate\Http\Request $request)
    {
        $query = Peminjaman::with(['user', 'ruangan', 'barang']);

        if ($request->filled('periode')) {
            $yearMonth = explode('-', $request->periode);
            if (count($yearMonth) == 2) {
                $query->whereYear('tanggal_pengajuan', $yearMonth[0])
                      ->whereMonth('tanggal_pengajuan', $yearMonth[1]);
            }
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_peminjaman', $request->jenis);
        }

        $peminjaman = $query->get();
        
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Laporan_Peminjaman.xls");
        
        echo "<table border='1'>";
        echo "<tr>
                <th>No</th>
                <th>ID Peminjaman</th>
                <th>Nama Peminjam</th>
                <th>Fasilitas</th>
                <th>Nama Gedung</th>
                <th>Tanggal Pengajuan</th>
                <th>Jam Peminjaman</th>
                <th>Tujuan</th>
                <th>Status</th>
              </tr>";
        
        $no = 1;
        foreach($peminjaman as $p) {
            $nama_peminjam = $p->user ? $p->user->nama_lengkap : '-';
            $fasilitas = $p->nama_fasilitas;
            
            $nama_gedung = '-';
            if ($p->jenis_peminjaman === 'ruangan' && $p->ruangan->count() > 0) {
                $nama_gedung = $p->ruangan->first()->nama_gedung ?? '-';
            }
            
            $tanggal = $p->tanggal_pengajuan ? $p->tanggal_pengajuan->format('d M Y') : '-';
            
            $jam_peminjaman = '-';
            if ($p->jam_mulai && $p->jam_selesai) {
                $jam_peminjaman = substr($p->jam_mulai, 0, 5) . ' - ' . substr($p->jam_selesai, 0, 5);
            }
            
            $tujuan = $p->tujuan_peminjaman;
            $status = ucfirst(str_replace('_', ' ', $p->status));
            
            echo "<tr>
                    <td>$no</td>
                    <td>PJM-{$p->id_peminjaman}</td>
                    <td>$nama_peminjam</td>
                    <td>$fasilitas</td>
                    <td>$nama_gedung</td>
                    <td>$tanggal</td>
                    <td>$jam_peminjaman</td>
                    <td>$tujuan</td>
                    <td>$status</td>
                  </tr>";
            $no++;
        }
        
        echo "</table>";
        exit;
    }

    public function exportPengembalianExcel(\Illuminate\Http\Request $request)
    {
        $ruanganQuery = \App\Models\PengembalianRuangan::with(['peminjaman.user', 'peminjaman.ruangan']);
        $barangQuery = \App\Models\PengembalianBarang::with(['peminjaman.user', 'peminjaman.barang']);

        if ($request->filled('periode')) {
            $yearMonth = explode('-', $request->periode);
            if (count($yearMonth) == 2) {
                $ruanganQuery->whereYear('tanggal_pengembalian', $yearMonth[0])
                             ->whereMonth('tanggal_pengembalian', $yearMonth[1]);
                $barangQuery->whereYear('tanggal', $yearMonth[0])
                            ->whereMonth('tanggal', $yearMonth[1]);
            }
        }

        if ($request->filled('jenis')) {
            if ($request->jenis === 'barang') {
                $ruanganQuery->whereRaw('1 = 0');
            } elseif ($request->jenis === 'ruangan') {
                $barangQuery->whereRaw('1 = 0');
            }
        }

        $ruangan = $ruanganQuery->get();
        $barang = $barangQuery->get();
        
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
                'tanggal_kembali' => $b->tanggal_pengembalian,
                'kondisi_kembali' => $b->detail->first()->kondisi_barang ?? 'baik',
                'status_pengembalian' => $b->peminjaman->status === 'selesai' ? 'dikonfirmasi_admin' : 'pending',
            ]);
        }
        
        $pengembalian = $pengembalian->sortByDesc('tanggal_kembali');

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Laporan_Pengembalian.xls");
        
        echo "<table border='1'>";
        echo "<tr>
                <th>No</th>
                <th>ID Pengembalian</th>
                <th>ID Peminjaman</th>
                <th>Nama Peminjam</th>
                <th>Fasilitas</th>
                <th>Nama Gedung</th>
                <th>Tanggal Kembali</th>
                <th>Jam Peminjaman</th>
                <th>Kondisi</th>
                <th>Status</th>
              </tr>";
              
        $no = 1;
        foreach($pengembalian as $p) {
            $id_pengembalian = "KMB-" . $p->id_pengembalian;
            $id_peminjaman = "PJM-" . $p->peminjaman->id_peminjaman;
            $nama_peminjam = $p->peminjaman->user ? $p->peminjaman->user->nama_lengkap : '-';
            $fasilitas = $p->peminjaman->nama_fasilitas;
            
            $nama_gedung = '-';
            if ($p->peminjaman->jenis_peminjaman === 'ruangan' && $p->peminjaman->ruangan->count() > 0) {
                $nama_gedung = $p->peminjaman->ruangan->first()->nama_gedung ?? '-';
            }
            
            // Format tanggal safely
            $tanggal = '-';
            if (is_string($p->tanggal_kembali)) {
                $tanggal = date('d M Y', strtotime($p->tanggal_kembali));
            } elseif ($p->tanggal_kembali instanceof \Carbon\Carbon || $p->tanggal_kembali instanceof \DateTime) {
                $tanggal = $p->tanggal_kembali->format('d M Y');
            }
            
            $jam_peminjaman = '-';
            if ($p->peminjaman->jam_mulai && $p->peminjaman->jam_selesai) {
                $jam_peminjaman = substr($p->peminjaman->jam_mulai, 0, 5) . ' - ' . substr($p->peminjaman->jam_selesai, 0, 5);
            }
            
            $kondisi = ucfirst($p->kondisi_kembali);
            $status = ucfirst(str_replace('_', ' ', $p->status_pengembalian));
            
            echo "<tr>
                    <td>$no</td>
                    <td>$id_pengembalian</td>
                    <td>$id_peminjaman</td>
                    <td>$nama_peminjam</td>
                    <td>$fasilitas</td>
                    <td>$nama_gedung</td>
                    <td>$tanggal</td>
                    <td>$jam_peminjaman</td>
                    <td>$kondisi</td>
                    <td>$status</td>
                  </tr>";
            $no++;
        }
        echo "</table>";
        exit;
    }
}
