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
        $totalPengajuanBaru = Peminjaman::where('status', 'menunggu_admin')->count();
        $menungguVerifikasi = Peminjaman::where('status', 'menunggu_admin')->count();
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
        $ruangan = Ruangan::with(['pic', 'fasilitas'])->get();
        $pics = \App\Models\User::penanggungJawab()->get();
        return view('admin.fasilitas.index', compact('ruangan', 'pics'));
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
            'pic_id' => 'required|exists:user,id_user',
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
                'pic_id' => $request->pic_id,
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
            'pic_id' => 'required|exists:user,id_user',
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
            'pic_id' => $request->pic_id,
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
        $pics = \App\Models\User::penanggungJawab()->get();
        return view('admin.inventaris.index', compact('barang', 'pics'));
    }
 
    public function inventarisStore(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'kode_barang' => 'nullable|string|max:50|unique:barang,kode_barang',
            'stok_tersedia' => 'required|integer|min:0',
            'foto_barang' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'keterangan' => 'nullable|string',
            'pic_id' => 'required|exists:user,id_user',
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
            'pic_id' => $request->pic_id,
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
            'pic_id' => 'required|exists:user,id_user',
        ]);
 
        $barang = Barang::findOrFail($id);
        $updateData = [
            'nama_barang' => $request->nama_barang,
            'kode_barang' => $request->kode_barang ?: 'BRG_' . uniqid(),
            'stok_tersedia' => $request->stok_tersedia,
            'keterangan' => $request->keterangan,
            'pic_id' => $request->pic_id,
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
        $peminjamanQueue = Peminjaman::with(['user', 'dosen', 'ruangan.pic', 'barang.pic', 'verifikasi'])
            ->where('status', 'menunggu_admin')
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
 
        $status = 'menunggu_admin';
        if ($request->status_pengajuan === 'disetujui') {
            $status = 'menunggu_kepala';
        } elseif ($request->status_pengajuan === 'ditolak') {
            $status = 'ditolak';
        } elseif ($request->status_pengajuan === 'revisi') {
            $status = 'revisi';
        }
 
        \Illuminate\Support\Facades\DB::transaction(function () use ($peminjaman, $status, $request, $id) {
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
        });
 
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

    public function apiGetSlots(Request $request)
    {
        try {
            $ruangan_id = $request->query('ruangan_id');
            $tanggal = $request->query('tanggal');

            if (!$ruangan_id || !$tanggal) {
                return response()->json(['message' => 'Fasilitas dan tanggal harus dipilih.'], 400);
            }

            // 1. Get existing slots from schedules table (with eager loading of related peminjaman and ruangan)
            $slots = \App\Models\Schedule::with(['ruangan', 'peminjaman'])
                ->where('ruangan_id', $ruangan_id)
                ->where('tanggal', $tanggal)
                ->orderBy('jam_mulai')
                ->get();

            // 2. Fetch active bookings (peminjaman) for this room on this date
            $peminjamans = \App\Models\Peminjaman::whereHas('ruangan', function($q) use ($ruangan_id) {
                    $q->where('ruangan.id_ruangan', $ruangan_id);
                })
                ->whereDate('tanggal_pengajuan', $tanggal)
                ->whereIn('status', ['menunggu_dosen', 'menunggu_admin', 'menunggu_kepala', 'menunggu_pic', 'siap_digunakan'])
                ->get();

            // 3. Populate slots data
            $slotsData = [];
            if ($slots->isEmpty()) {
                $defaultTimes = [
                    ['start' => '08:00:00', 'end' => '09:00:00', 'label' => '08.00'],
                    ['start' => '09:00:00', 'end' => '10:00:00', 'label' => '09.00'],
                    ['start' => '10:00:00', 'end' => '11:00:00', 'label' => '10.00'],
                    ['start' => '11:00:00', 'end' => '12:00:00', 'label' => '11.00'],
                    ['start' => '12:00:00', 'end' => '13:00:00', 'label' => '12.00'],
                    ['start' => '13:00:00', 'end' => '14:00:00', 'label' => '13.00'],
                    ['start' => '14:00:00', 'end' => '15:00:00', 'label' => '14.00'],
                ];

                foreach ($defaultTimes as $t) {
                    $status = 'tersedia';
                    $peminjaman_id = null;

                    // Automatically associate booking matching the slot start hour
                    foreach ($peminjamans as $p) {
                        $hour = (int)$p->tanggal_pengajuan->format('H');
                        $startHour = (int)explode(':', $t['start'])[0];
                        if ($hour === $startHour) {
                            $status = $p->status === 'siap_digunakan' ? 'dipinjam' : 'pending';
                            $peminjaman_id = $p->id_peminjaman;
                            break;
                        }
                    }

                    $slotsData[] = [
                        'jam_mulai' => $t['start'],
                        'jam_selesai' => $t['end'],
                        'label' => $t['label'],
                        'status' => $status,
                        'peminjaman_id' => $peminjaman_id
                    ];
                }
            } else {
                foreach ($slots as $s) {
                    $status = $s->status;
                    $peminjaman_id = $s->peminjaman_id;

                    $sHour = (int)explode(':', $s->jam_mulai)[0];
                    foreach ($peminjamans as $p) {
                        $hour = (int)$p->tanggal_pengajuan->format('H');
                        if ($hour === $sHour) {
                            $status = $p->status === 'siap_digunakan' ? 'dipinjam' : 'pending';
                            $peminjaman_id = $p->id_peminjaman;
                            break;
                        }
                    }

                    $slotsData[] = [
                        'jam_mulai' => $s->jam_mulai,
                        'jam_selesai' => $s->jam_selesai,
                        'label' => date('H.i', strtotime($s->jam_mulai)),
                        'status' => $status,
                        'peminjaman_id' => $peminjaman_id
                    ];
                }
            }

            // 4. Validate conflicts (overlapping times where at least one slot is occupied)
            $conflicts = [];
            $count = count($slotsData);
            for ($i = 0; $i < $count; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {
                    $s1 = $slotsData[$i];
                    $s2 = $slotsData[$j];

                    $s1_start = strtotime($s1['jam_mulai']);
                    $s1_end = strtotime($s1['jam_selesai']);
                    $s2_start = strtotime($s2['jam_mulai']);
                    $s2_end = strtotime($s2['jam_selesai']);

                    // Overlap check
                    if ($s1_start < $s2_end && $s1_end > $s2_start) {
                        if ($s1['status'] !== 'tersedia' || $s2['status'] !== 'tersedia') {
                            $conflicts[] = "Jadwal Bentrok: Slot " . date('H.i', $s1_start) . "-" . date('H.i', $s1_end) . " dan " . date('H.i', $s2_start) . "-" . date('H.i', $s2_end) . " tumpang tindih.";
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'slots' => $slotsData,
                'peminjamans' => $peminjamans->map(function($p) {
                    return [
                        'id_peminjaman' => $p->id_peminjaman,
                        'nama_kegiatan' => $p->nama_kegiatan,
                        'keterangan' => $p->keterangan,
                        'status' => $p->status
                    ];
                }),
                'conflicts' => $conflicts,
                'has_conflict' => !empty($conflicts)
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('apiGetSlots error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error: ' . $e->getMessage()], 500);
        }
    }

    public function apiSaveSlots(Request $request)
    {
        try {
            $ruangan_id = $request->input('ruangan_id');
            $tanggal = $request->input('tanggal');
            $slots = $request->input('slots', []);

            if (!$ruangan_id || !$tanggal) {
                return response()->json(['message' => 'Fasilitas dan tanggal harus dipilih.'], 400);
            }

            \Illuminate\Support\Facades\DB::beginTransaction();

            // Clear old slots
            \App\Models\Schedule::where('ruangan_id', $ruangan_id)
                ->where('tanggal', $tanggal)
                ->delete();

            // Save new slots
            foreach ($slots as $s) {
                \App\Models\Schedule::create([
                    'ruangan_id' => $ruangan_id,
                    'tanggal' => $tanggal,
                    'jam_mulai' => $s['jam_mulai'],
                    'jam_selesai' => $s['jam_selesai'],
                    'status' => $s['status'],
                    'peminjaman_id' => $s['peminjaman_id'] ?: null,
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal penggunaan fasilitas berhasil disimpan.'
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('apiSaveSlots error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error: ' . $e->getMessage()], 500);
        }
    }
 
    // Data Peminjaman
    public function peminjamanIndex()
    {
        $search = request('search');
        $status = request('status');
        $date = request('date');
        
        $query = Peminjaman::with(['user', 'dosen', 'ruangan.pic', 'barang.pic'])
            ->latest('id_peminjaman');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kegiatan', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($sub) use ($search) {
                      $sub->where('nama_lengkap', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($status) {
            if ($status === 'pending') {
                $query->whereIn('status', ['menunggu_dosen', 'menunggu_admin', 'menunggu_kepala', 'menunggu_pic', 'pending', 'revisi']);
            } elseif ($status === 'diterima') {
                $query->where('status', 'siap_digunakan');
            } elseif ($status === 'selesai') {
                $query->where('status', 'selesai');
            } elseif ($status === 'ditolak') {
                $query->where('status', 'ditolak');
            }
        }

        if ($date) {
            $query->whereDate('tanggal_pengajuan', $date);
        }

        $peminjaman = $query->paginate(10)->withQueryString();

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
        $ruangan = PengembalianRuangan::with(['peminjaman.user', 'peminjaman.ruangan'])
            ->where('status', 'menunggu_admin')
            ->get();
        $barang = PengembalianBarang::with(['peminjaman.user', 'peminjaman.barang'])
            ->where('status', 'menunggu_admin')
            ->get();
 
        $pengembalian = collect();
 
        foreach ($ruangan as $r) {
            if ($r->peminjaman) {
                $pengembalian->push((object)[
                    'id_pengembalian' => $r->id_pengembalian_ruangan,
                    'peminjaman' => $r->peminjaman,
                    'kategori' => 'ruangan',
                    'tanggal_kembali' => $r->tanggal_pengembalian,
                    'kondisi_kembali' => 'baik',
                    'status_pengembalian' => $r->status,
                ]);
            }
        }
 
        foreach ($barang as $b) {
            if ($b->peminjaman) {
                $pengembalian->push((object)[
                    'id_pengembalian' => $b->id_pengembalian_barang,
                    'peminjaman' => $b->peminjaman,
                    'kategori' => 'barang',
                    'tanggal_kembali' => $b->tanggal,
                    'kondisi_kembali' => 'baik',
                    'status_pengembalian' => $b->status,
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
 
        $decision = $request->input('status_keputusan', 'disetujui');
        $statusPengembalian = $decision === 'disetujui' ? 'selesai' : 'ditolak';

        if ($peminjaman) {
            \Illuminate\Support\Facades\DB::transaction(function() use ($peminjaman, $statusPengembalian, $kategori, $id, $request) {
                // Update return table status
                if ($kategori === 'ruangan') {
                    $peminjaman->pengembalianRuangan->update(['status' => $statusPengembalian]);
                } else {
                    $peminjaman->pengembalianBarang->update(['status' => $statusPengembalian]);
                }

                // Update main booking table status
                $peminjaman->update([
                    'status' => $statusPengembalian
                ]);
 
                VerifikasiPengembalian::create([
                    'id_pengembalian_ruangan' => $kategori === 'ruangan' ? $id : null,
                    'id_pengembalian_barang' => $kategori === 'barang' ? $id : null,
                    'id_verifikator' => auth()->id() ?: 3,
                    'peran_verifikasi' => 'Admin SBUM',
                    'status' => $statusPengembalian === 'selesai' ? 'disetujui' : 'ditolak',
                    'catatan' => $request->input('catatan', 'Pengembalian terverifikasi.'),
                    'tanggal' => now(),
                ]);
            });
        }
 
        return redirect()->route('admin.verifikasi-pengembalian')->with('success', 'Verifikasi pengembalian berhasil.');
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
