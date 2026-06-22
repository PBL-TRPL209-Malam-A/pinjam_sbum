<?php
 
namespace App\Http\Controllers;
 
use App\Models\Ruangan;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\VerifikasiPeminjaman;
use App\Models\PengembalianRuangan;
use App\Models\PengembalianBarang;
use App\Models\VerifikasiPengembalian;
use Illuminate\Http\Request;
 
class AdminController extends Controller
{
    // Dashboard Admin
    public function dashboard()
    {
        $totalPengajuanBaru = Peminjaman::where('status', 'pending')->count();
        $menungguVerifikasi = Peminjaman::where('status', 'pending')->count();
        $totalJadwalBentrok = 3; // Mocked or calculated value
        
        $returnPending = PengembalianRuangan::whereDoesntHave('peminjaman', function ($q) {
            $q->where('status', 'selesai');
        })->count() + PengembalianBarang::whereDoesntHave('peminjaman', function ($q) {
            $q->where('status', 'selesai');
        })->count();
 
        $recentPeminjaman = Peminjaman::with(['user', 'ruangan', 'barang'])
            ->latest('id_peminjaman')
            ->take(5)
            ->get();
 
        return view('admin.dashboard', compact(
            'totalPengajuanBaru',
            'menungguVerifikasi',
            'totalJadwalBentrok',
            'returnPending',
            'recentPeminjaman'
        ));
    }
 
    // Kelola Data Fasilitas (Ruangan)
    public function fasilitasIndex()
    {
        $ruangan = Ruangan::with('pic')->get();
        return view('admin.fasilitas.index', compact('ruangan'));
    }
 
    public function fasilitasStore(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:150',
            'nama_gedung' => 'nullable|string|max:150',
            'kode_ruangan' => 'required|string|max:50|unique:ruangan,kode_ruangan',
            'kapasitas' => 'nullable|integer',
            'lantai' => 'nullable|string|max:20',
            'status_ruangan' => 'required|in:tersedia,tidak tersedia,maintenance',
            'foto_ruangan' => 'nullable|image|mimes:jpg,jpeg,webp,png|max:10240',
            'deskripsi_ruangan' => 'nullable|string',
            'fasilitas_items' => 'nullable|array',
            'fasilitas_items.*.nama_fasilitas' => 'nullable|string|max:150',
            'fasilitas_items.*.jumlah' => 'nullable|integer|min:1',
            'fasilitas_items.*.keterangan' => 'nullable|string',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_ruangan')) {
            $file = $request->file('foto_ruangan');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/ruangan'), $fileName);
            $fotoPath = 'uploads/ruangan/' . $fileName;
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $ruangan = Ruangan::create([
                'nama_ruangan' => $request->nama_ruangan,
                'nama_gedung' => $request->nama_gedung,
                'kode_ruangan' => $request->kode_ruangan,
                'kapasitas' => $request->kapasitas,
                'lantai' => $request->lantai,
                'status_ruangan' => $request->status_ruangan,
                'foto_ruangan' => $fotoPath,
                'deskripsi_ruangan' => $request->deskripsi_ruangan,
            ]);

            if (!empty($request->fasilitas_items)) {
                foreach ($request->fasilitas_items as $item) {
                    if (!empty($item['nama_fasilitas'])) {
                        \App\Models\FasilitasRuangan::create([
                            'id_ruangan' => $ruangan->id_ruangan,
                            'nama_fasilitas' => $item['nama_fasilitas'],
                            'jumlah' => $item['jumlah'] ?? 1,
                            'keterangan' => $item['keterangan'] ?? null,
                        ]);
                    }
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return back()->with('success', 'Fasilitas ruangan beserta detailnya berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function fasilitasUpdate(Request $request, $id)
    {
        \Illuminate\Support\Facades\Log::info('fasilitasUpdate request data:', $request->all());
        $validated = $request->validate([
            'nama_ruangan' => 'required|string|max:150',
            'nama_gedung' => 'nullable|string|max:150',
            'kode_ruangan' => 'required|string|max:50|unique:ruangan,kode_ruangan,' . $id . ',id_ruangan',
            'kapasitas' => 'nullable|integer',
            'lantai' => 'nullable|string|max:20',
            'status_ruangan' => 'required|in:tersedia,tidak tersedia,maintenance',
            'foto_ruangan' => 'nullable|image|mimes:jpg,jpeg,webp,png|max:10240',
            'deskripsi_ruangan' => 'nullable|string',
            'fasilitas_items' => 'nullable|array',
            'fasilitas_items.*.id_fasilitas' => 'nullable|integer',
            'fasilitas_items.*.nama_fasilitas' => 'nullable|string|max:150',
            'fasilitas_items.*.jumlah' => 'nullable|integer|min:1',
            'fasilitas_items.*.keterangan' => 'nullable|string',
        ]);

        $ruangan = Ruangan::findOrFail($id);
        
        $updateData = [
            'nama_ruangan' => $request->nama_ruangan,
            'nama_gedung' => $request->nama_gedung,
            'kode_ruangan' => $request->kode_ruangan,
            'kapasitas' => $request->kapasitas,
            'lantai' => $request->lantai,
            'status_ruangan' => $request->status_ruangan,
            'deskripsi_ruangan' => $request->deskripsi_ruangan,
        ];

        if ($request->hasFile('foto_ruangan')) {
            // Hapus foto lama jika ada
            if ($ruangan->foto_ruangan && file_exists(public_path($ruangan->foto_ruangan))) {
                @unlink(public_path($ruangan->foto_ruangan));
            }
            $file = $request->file('foto_ruangan');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/ruangan'), $fileName);
            $updateData['foto_ruangan'] = 'uploads/ruangan/' . $fileName;
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $ruangan->update($updateData);

            $submittedIds = [];
            if (!empty($request->fasilitas_items)) {
                foreach ($request->fasilitas_items as $item) {
                    if (!empty($item['nama_fasilitas'])) {
                        if (!empty($item['id_fasilitas'])) {
                            // Update existing facility
                            $f = \App\Models\FasilitasRuangan::where('id_ruangan', $id)
                                ->where('id_fasilitas', $item['id_fasilitas'])
                                ->first();
                            if ($f) {
                                $f->update([
                                    'nama_fasilitas' => $item['nama_fasilitas'],
                                    'jumlah' => $item['jumlah'] ?? 1,
                                    'keterangan' => $item['keterangan'] ?? null,
                                ]);
                                $submittedIds[] = $f->id_fasilitas;
                            }
                        } else {
                            // Create new facility
                            $newF = \App\Models\FasilitasRuangan::create([
                                'id_ruangan' => $id,
                                'nama_fasilitas' => $item['nama_fasilitas'],
                                'jumlah' => $item['jumlah'] ?? 1,
                                'keterangan' => $item['keterangan'] ?? null,
                            ]);
                            $submittedIds[] = $newF->id_fasilitas;
                        }
                    }
                }
            }

            // Hapus fasilitas pendukung yang tidak dikirimkan lagi
            \App\Models\FasilitasRuangan::where('id_ruangan', $id)
                ->whereNotIn('id_fasilitas', array_filter($submittedIds))
                ->delete();

            \Illuminate\Support\Facades\DB::commit();
            return back()->with('success', 'Fasilitas ruangan berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }
 
    public function fasilitasDestroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->delete();
        return back()->with('success', 'Fasilitas ruangan berhasil dihapus.');
    }
 
    // Kelola Barang Inventaris
    public function inventarisIndex()
    {
        $barang = Barang::with('pic')->get();
        return view('admin.inventaris.index', compact('barang'));
    }
 
    public function inventarisStore(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'kode_barang' => 'nullable|string|max:50|unique:barang,kode_barang',
            'stok_tersedia' => 'required|integer|min:0',
            'foto_barang' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'keterangan' => 'nullable|string',
        ]);
 
        $fotoPath = null;
        if ($request->hasFile('foto_barang')) {
            $file = $request->file('foto_barang');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/barang'), $fileName);
            $fotoPath = 'uploads/barang/' . $fileName;
        }

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'kode_barang' => $request->kode_barang ?: 'BRG_' . uniqid(),
            'stok_tersedia' => $request->stok_tersedia,
            'foto_barang' => $fotoPath,
            'keterangan' => $request->keterangan,
        ]);
 
        return back()->with('success', 'Barang inventaris berhasil ditambahkan.');
    }
 
    public function inventarisUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'kode_barang' => 'nullable|string|max:50|unique:barang,kode_barang,' . $id . ',id_barang',
            'stok_tersedia' => 'required|integer|min:0',
            'foto_barang' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'keterangan' => 'nullable|string',
        ]);
 
        $barang = Barang::findOrFail($id);
        $updateData = [
            'nama_barang' => $request->nama_barang,
            'kode_barang' => $request->kode_barang ?: 'BRG_' . uniqid(),
            'stok_tersedia' => $request->stok_tersedia,
            'keterangan' => $request->keterangan,
        ];

        if ($request->hasFile('foto_barang')) {
            // Hapus foto lama jika ada
            if ($barang->foto_barang && file_exists(public_path($barang->foto_barang))) {
                @unlink(public_path($barang->foto_barang));
            }
            $file = $request->file('foto_barang');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/barang'), $fileName);
            $updateData['foto_barang'] = 'uploads/barang/' . $fileName;
        }

        $barang->update($updateData);
 
        return back()->with('success', 'Barang inventaris berhasil diperbarui.');
    }
 
    public function inventarisDestroy($id)
    {
        $barang = Barang::findOrFail($id);
        if ($barang->foto_barang && file_exists(public_path($barang->foto_barang))) {
            @unlink(public_path($barang->foto_barang));
        }
        $barang->delete();
 
        return back()->with('success', 'Barang inventaris berhasil dihapus.');
    }
 
    // Verifikasi Peminjaman Queue
    public function verifikasiPeminjamanIndex()
    {
        $peminjamanQueue = Peminjaman::with(['user', 'ruangan', 'barang', 'verifikasi'])
            ->whereIn('status', ['pending', 'disetujui'])
            ->get();
 
        return view('admin.peminjaman.verifikasi', compact('peminjamanQueue'));
    }
 
    public function verifikasiPeminjamanStore(Request $request, $id)
    {
        $request->validate([
            'status_pengajuan' => 'required|in:disetujui,ditolak,revisi',
            'catatan' => 'nullable|string',
        ]);
 
        $peminjaman = Peminjaman::findOrFail($id);
 
        $status = 'pending';
        if ($request->status_pengajuan === 'disetujui') {
            $status = 'disetujui';
        } elseif ($request->status_pengajuan === 'ditolak') {
            $status = 'ditolak';
        }
 
        $peminjaman->update([
            'status' => $status
        ]);
 
        VerifikasiPeminjaman::create([
            'id_peminjaman' => $id,
            'id_verifikator' => auth()->id() ?: 3, // fallback to A001 Admin if not logged in
            'peran_verifikasi' => 'Admin SBUM',
            'jenis_verifikasi' => 'Verifikasi Operasional',
            'status' => $request->status_pengajuan === 'disetujui' ? 'disetujui' : ($request->status_pengajuan === 'ditolak' ? 'ditolak' : 'pending'),
            'catatan' => $request->catatan,
            'tanggal' => now(),
        ]);
 
        return redirect()->route('admin.verifikasi-peminjaman')->with('success', 'Status verifikasi peminjaman berhasil diperbarui.');
    }
 
    // Atur Jadwal
    public function jadwalIndex(Request $request)
    {
        $ruangan = Ruangan::all();
        $selectedRuanganId = $request->input('ruangan_id', $ruangan->first()->id_ruangan ?? null);
        $selectedDate = $request->input('tanggal', date('Y-m-d'));
 
        return view('admin.jadwal.index', compact('ruangan', 'selectedRuanganId', 'selectedDate'));
    }
 
    public function jadwalStore(Request $request)
    {
        return back()->with('success', 'Konfigurasi slot jadwal berhasil disimpan.');
    }
 
    // Data Peminjaman
    public function peminjamanIndex()
    {
        $peminjaman = Peminjaman::with(['user', 'ruangan', 'barang'])->get();
        return view('admin.peminjaman.index', compact('peminjaman'));
    }
 
    public function peminjamanVerifikasi(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        $request->validate([
            'status_pengajuan' => 'required|in:verif_admin,ditolak,disetujui,pending',
        ]);
 
        $status = $request->status_pengajuan;
        if ($status === 'verif_admin' || $status === 'disetujui') {
            $status = 'disetujui';
        }
 
        $peminjaman->update([
            'status' => $status
        ]);
 
        return back()->with('success', 'Status peminjaman berhasil diperbarui.');
    }
 
    // Pengembalian & Verifikasi Pengembalian
    public function pengembalianIndex()
    {
        $ruangan = PengembalianRuangan::with(['peminjaman.user', 'peminjaman.ruangan'])->get();
        $barang = PengembalianBarang::with(['peminjaman.user', 'peminjaman.barang'])->get();
 
        $pengembalian = collect();
 
        foreach ($ruangan as $r) {
            $pengembalian->push((object)[
                'id_pengembalian' => $r->id_pengembalian_ruangan,
                'peminjaman' => $r->peminjaman,
                'kategori' => 'ruangan',
                'tanggal_kembali' => $r->tanggal_pengembalian,
                'kondisi_kembali' => 'baik',
                'status_pengembalian' => $r->peminjaman && $r->peminjaman->status === 'selesai' ? 'dikonfirmasi_admin' : 'pending',
            ]);
        }
 
        foreach ($barang as $b) {
            $pengembalian->push((object)[
                'id_pengembalian' => $b->id_pengembalian_barang,
                'peminjaman' => $b->peminjaman,
                'kategori' => 'barang',
                'tanggal_kembali' => $b->tanggal,
                'kondisi_kembali' => 'baik',
                'status_pengembalian' => $b->peminjaman && $b->peminjaman->status === 'selesai' ? 'dikonfirmasi_admin' : 'pending',
            ]);
        }
 
        return view('admin.pengembalian.index', compact('pengembalian'));
    }
 
    public function pengembalianVerifikasi(Request $request, $id)
    {
        $kategori = $request->query('kategori', 'ruangan');
        if ($kategori === 'ruangan') {
            $r = PengembalianRuangan::find($id);
            $peminjaman = $r ? $r->peminjaman : null;
        } else {
            $b = PengembalianBarang::find($id);
            $peminjaman = $b ? $b->peminjaman : null;
        }
 
        if ($peminjaman) {
            $peminjaman->update([
                'status' => 'selesai'
            ]);
        }
 
        return back()->with('success', 'Pengembalian berhasil diverifikasi.');
    }
 
    public function verifikasiPengembalianIndex()
    {
        $ruangan = PengembalianRuangan::with(['peminjaman.user', 'peminjaman.ruangan'])->get();
        $barang = PengembalianBarang::with(['peminjaman.user', 'peminjaman.barang'])->get();
 
        $pengembalian = collect();
 
        foreach ($ruangan as $r) {
            if ($r->peminjaman && $r->peminjaman->status !== 'selesai') {
                $pengembalian->push((object)[
                    'id_pengembalian' => $r->id_pengembalian_ruangan,
                    'peminjaman' => $r->peminjaman,
                    'kategori' => 'ruangan',
                    'tanggal_kembali' => $r->tanggal_pengembalian,
                    'kondisi_kembali' => 'baik',
                    'status_pengembalian' => 'pending',
                ]);
            }
        }
 
        foreach ($barang as $b) {
            if ($b->peminjaman && $b->peminjaman->status !== 'selesai') {
                $pengembalian->push((object)[
                    'id_pengembalian' => $b->id_pengembalian_barang,
                    'peminjaman' => $b->peminjaman,
                    'kategori' => 'barang',
                    'tanggal_kembali' => $b->tanggal,
                    'kondisi_kembali' => 'baik',
                    'status_pengembalian' => 'pending',
                ]);
            }
        }
 
        return view('admin.pengembalian.verifikasi', compact('pengembalian'));
    }
 
    public function verifikasiPengembalianStore(Request $request, $id)
    {
        $kategori = $request->input('kategori', 'ruangan');
        if ($kategori === 'ruangan') {
            $r = PengembalianRuangan::findOrFail($id);
            $peminjaman = $r->peminjaman;
        } else {
            $b = PengembalianBarang::findOrFail($id);
            $peminjaman = $b->peminjaman;
        }
 
        if ($peminjaman) {
            $peminjaman->update([
                'status' => 'selesai'
            ]);
 
            VerifikasiPengembalian::create([
                'id_pengembalian_ruangan' => $kategori === 'ruangan' ? $id : null,
                'id_pengembalian_barang' => $kategori === 'barang' ? $id : null,
                'id_verifikator' => auth()->id() ?: 3,
                'peran_verifikasi' => 'Admin SBUM',
                'status' => 'disetujui',
                'catatan' => $request->input('catatan', 'Pengembalian terverifikasi.'),
                'tanggal' => now(),
            ]);
        }
 
        return redirect()->route('admin.verifikasi-pengembalian')->with('success', 'Pengembalian berhasil diverifikasi.');
    }
 
    // Profil Admin
    public function profilIndex()
    {
        $user = auth()->user();
        return view('admin.profil.index', compact('user'));
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
