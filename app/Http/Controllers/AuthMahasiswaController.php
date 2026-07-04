<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthMahasiswaController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $redirectRoute = match (true) {
                $user->isAdmin() && \Illuminate\Support\Facades\Route::has('admin.dashboard') => 'admin.dashboard',
                $user->isPic() && \Illuminate\Support\Facades\Route::has('pic.dashboard') => 'pic.dashboard',
                $user->isDosen() && \Illuminate\Support\Facades\Route::has('dosen.dashboard') => 'dosen.dashboard',
                $user->isMahasiswa() && \Illuminate\Support\Facades\Route::has('mahasiswa.dashboard') => 'mahasiswa.dashboard',
                $user->isKepalaSbum() && \Illuminate\Support\Facades\Route::has('kepalasbum.persetujuan') => 'kepalasbum.persetujuan',
                $user->isPamdal() && \Illuminate\Support\Facades\Route::has('pamdal.dashboard') => 'pamdal.dashboard',
                default => 'mahasiswa.dashboard',
            };
            return redirect()->route($redirectRoute);
        }

        return view('auth.login');
    }

    public function showRegister()
    {
        if (auth()->check()) {
            return redirect()->route('mahasiswa.dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'nim' => 'required|string|max:20|unique:user,nim',
            'email' => 'required|email|max:150|unique:user,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'nama_lengkap' => $validated['nama_lengkap'],
                'nim' => $validated['nim'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            DB::table('user_role')->insert([
                'user_id' => $user->id_user,
                'role_id' => 1,
            ]);

            DB::commit();

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('mahasiswa.dashboard')
                ->with('success', 'Registrasi mahasiswa berhasil.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Registrasi gagal: ' . $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('nim', $request->login)
            ->orWhere('nik', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withInput()
                ->withErrors([
                    'login' => 'Email/NIM/NIP atau password salah.',
                ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        $redirectRoute = match (true) {
            $user->isAdmin() && \Illuminate\Support\Facades\Route::has('admin.dashboard') => 'admin.dashboard',
            $user->isPic() && \Illuminate\Support\Facades\Route::has('pic.dashboard') => 'pic.dashboard',
            $user->isDosen() && \Illuminate\Support\Facades\Route::has('dosen.dashboard') => 'dosen.dashboard',
            $user->isMahasiswa() && \Illuminate\Support\Facades\Route::has('mahasiswa.dashboard') => 'mahasiswa.dashboard',
            $user->isKepalaSbum() && \Illuminate\Support\Facades\Route::has('kepalasbum.persetujuan') => 'kepalasbum.persetujuan',
            $user->isPamdal() && \Illuminate\Support\Facades\Route::has('pamdal.dashboard') => 'pamdal.dashboard',
            default => 'home',
        };

        return redirect()->route($redirectRoute);
    }

    public function dashboard()
    {
        if (!auth()->check() || !auth()->user()->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        $userId = auth()->id();

        // 1. Stats
        $pengajuanAktif = \App\Models\Peminjaman::where('user_id', $userId)
            ->whereNotIn('status', ['ditolak', 'dikembalikan', 'batal', 'selesai'])
            ->count();
        
        $menungguPersetujuan = \App\Models\Peminjaman::where('user_id', $userId)
            ->whereIn('status', ['menunggu_dosen', 'menunggu_admin', 'menunggu_kepala_sbum', 'menunggu_pic'])
            ->count();

        $riwayatSelesai = \App\Models\Peminjaman::where('user_id', $userId)
            ->whereIn('status', ['dikembalikan', 'selesai', 'ditolak', 'batal'])
            ->count();

        // Using recent updates as 'notifications'
        $notifikasiBaru = \App\Models\Peminjaman::where('user_id', $userId)
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->where('tanggal_pengajuan', '>=', now()->subDays(3)->toDateString())
            ->count();

        // 2. Jadwal Terdekat
        $jadwalTerdekat = \App\Models\Peminjaman::with(['ruangan', 'barang'])
            ->where('user_id', $userId)
            ->where('status', 'disetujui')
            ->whereDate('tanggal_pengajuan', '>=', now()->toDateString())
            ->orderBy('tanggal_pengajuan', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->first();

        // 3. Status Pengajuan Terbaru
        $pengajuanTerbaru = \App\Models\Peminjaman::with(['ruangan', 'barang'])
            ->where('user_id', $userId)
            ->orderBy('id_peminjaman', 'desc')
            ->take(3)
            ->get();

        return view('mahasiswa.dashboard', compact(
            'pengajuanAktif', 
            'menungguPersetujuan', 
            'riwayatSelesai', 
            'notifikasiBaru',
            'jadwalTerdekat',
            'pengajuanTerbaru'
        ));
    }

    public function fasilitas(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        $search = $request->input('search');
        $category = $request->input('category', 'Semua');
        $location = $request->input('location', 'Semua Gedung');
        $status = $request->input('status', 'Semua');

        // 1. Ambil opsi daftar gedung untuk dropdown filter secara dinamis
        $buildings = \App\Models\Ruangan::select('nama_gedung')
            ->whereNotNull('nama_gedung')
            ->distinct()
            ->pluck('nama_gedung');

        $facilities = collect();

        // 2. Query Data Ruangan jika kategori adalah 'Semua' atau 'Ruangan'
        if ($category === 'Semua' || $category === 'Ruangan') {
            $rooms = \App\Models\Ruangan::with('pic')
                ->search($search)
                ->filterByLocation($location)
                ->filterByStatus($status)
                ->get()
                ->map(function ($room) {
                    return (object)[
                        'id' => $room->id_ruangan,
                        'nama' => $room->nama_ruangan,
                        'kategori' => 'Ruangan',
                        'kode' => $room->kode_ruangan,
                        'detail_meta' => "Kapasitas {$room->kapasitas} orang",
                        'status' => $room->status_ruangan, // tersedia, tidak tersedia, maintenance
                        'foto' => $room->foto_ruangan,
                        'lokasi' => $room->nama_gedung ?? 'N/A',
                    ];
                });
            $facilities = $facilities->concat($rooms);
        }

        // 3. Query Data Barang jika kategori adalah 'Semua' atau 'Inventaris'
        if ($category === 'Semua' || $category === 'Inventaris') {
            $items = \App\Models\Barang::with('pic')
                ->search($search)
                ->filterByLocation($location)
                ->filterByStatus($status)
                ->get()
                ->map(function ($item) {
                    $itemStatus = 'tidak tersedia';
                    if ($item->stok_tersedia > 5) {
                        $itemStatus = 'tersedia';
                    } elseif ($item->stok_tersedia > 0) {
                        $itemStatus = 'terbatas';
                    }

                    return (object)[
                        'id' => $item->id_barang,
                        'nama' => $item->nama_barang,
                        'kategori' => 'Inventaris',
                        'kode' => $item->kode_barang,
                        'detail_meta' => "Stok: {$item->stok_tersedia} unit",
                        'status' => $itemStatus,
                        'foto' => $item->foto_barang,
                        'lokasi' => $item->keterangan ?? 'Gudang SBUM',
                    ];
                });
            $facilities = $facilities->concat($items);
        }

        // 4. Custom Pagination untuk Gabungan Collection
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 6;
        $currentPageItems = $facilities->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $paginatedFacilities = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $facilities->count(),
            $perPage,
            $currentPage,
            [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'query' => $request->query()
            ]
        );

        return view('mahasiswa.fasilitas', [
            'facilities' => $paginatedFacilities,
            'buildings' => $buildings,
            'selectedCategory' => $category,
            'selectedLocation' => $location,
            'selectedStatus' => $status,
            'search' => $search
        ]);
    }

    public function fasilitasDetail(Request $request)
    {
        try {
            if (!auth()->check() || !auth()->user()->isMahasiswa()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $id = $request->query('id');
            $category = $request->query('kategori');

            if ($category === 'Ruangan') {
                $room = \App\Models\Ruangan::with(['pic', 'fasilitas'])->find($id);
                if (!$room) {
                    return response()->json(['message' => 'Ruangan tidak ditemukan'], 404);
                }
                return response()->json([
                    'success' => true,
                    'kategori' => 'Ruangan',
                    'data' => [
                        'nama' => $room->nama_ruangan,
                        'kode' => $room->kode_ruangan,
                        'gedung' => $room->nama_gedung,
                        'lantai' => $room->lantai,
                        'kapasitas' => $room->kapasitas,
                        'status' => $room->status_ruangan,
                        'foto' => $room->foto_ruangan ? asset($room->foto_ruangan) : null,
                        'deskripsi' => $room->deskripsi_ruangan ?? 'Tidak ada deskripsi tambahan.',
                        'pic' => $room->pic ? $room->pic->nama_lengkap : 'N/A',
                        'fasilitas_pendukung' => $room->fasilitas->map(function ($f) {
                            return [
                                'nama' => $f->nama_fasilitas,
                                'jumlah' => $f->jumlah,
                                'keterangan' => $f->keterangan ?? '-'
                            ];
                        })
                    ]
                ]);
            } elseif ($category === 'Inventaris') {
                $item = \App\Models\Barang::with('pic')->find($id);
                if (!$item) {
                    return response()->json(['message' => 'Barang tidak ditemukan'], 404);
                }

                $itemStatus = 'tidak tersedia';
                if ($item->stok_tersedia > 5) {
                    $itemStatus = 'tersedia';
                } elseif ($item->stok_tersedia > 0) {
                    $itemStatus = 'terbatas';
                }

                // Calculate total stock (current available stock + sum of quantities in active/pending/approved loans)
                $borrowed_qty = \App\Models\DetailPeminjamanBarang::where('barang_id', $item->id_barang)
                    ->whereHas('peminjaman', function($q) {
                        $q->whereIn('status', ['pending', 'disetujui']);
                    })
                    ->sum('jumlah');
                $stok_total = $item->stok_tersedia + $borrowed_qty;

                return response()->json([
                    'success' => true,
                    'kategori' => 'Inventaris',
                    'data' => [
                        'nama' => $item->nama_barang,
                        'kode' => $item->kode_barang,
                        'stok' => $item->stok_tersedia,
                        'stok_total' => $stok_total,
                        'status' => $itemStatus,
                        'foto' => $item->foto_barang ? asset($item->foto_barang) : null,
                        'keterangan' => $item->keterangan ?? 'Tidak ada keterangan tambahan.',
                        'pic' => $item->pic ? $item->pic->nama_lengkap : 'N/A'
                    ]
                ]);
            }

            return response()->json(['message' => 'Kategori tidak valid'], 400);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Fasilitas Detail AJAX error: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan internal server: ' . $e->getMessage()], 500);
        }
    }

    public function jadwal()
    {
        if (!auth()->check() || !auth()->user()->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        $rooms = \App\Models\Ruangan::available()->get();
        $items = \App\Models\Barang::where('stok_tersedia', '>', 0)->get();

        return view('mahasiswa.jadwal', compact('rooms', 'items'));
    }

    public function pengajuan(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        // Active facilities
        $rooms = \App\Models\Ruangan::available()->get();
        $items = \App\Models\Barang::where('stok_tersedia', '>', 0)->get();

        // Allowed responsible staff - only Dosen can be academic sponsor
        $staff = User::whereHas('roles', function ($q) {
            $q->where('role.nama_role', 'Dosen')
              ->orWhere('role.id_role', 2);
        })->with('roles')->get();

        // Allowed PICs
        $pics = User::whereHas('roles', function ($q) {
            $q->where('role.nama_role', 'PIC Fasilitas')
              ->orWhere('role.id_role', 5);
        })->with('roles')->get();

        $selectedFacilityId = $request->query('facility_id');

        return view('mahasiswa.pengajuan', compact('rooms', 'items', 'staff', 'pics', 'selectedFacilityId'));
    }

    public function pengajuanStore(\App\Http\Requests\BookingStoreRequest $request)
    {
        if (!auth()->check() || !auth()->user()->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        $facilityParts = explode('-', $request->input('facility_id'), 2);
        if (count($facilityParts) < 2) {
            return back()->withErrors(['facility_id' => 'Fasilitas tidak valid.'])->withInput();
        }

        $type = $facilityParts[0];
        $id = $facilityParts[1];

        if (strtolower($type) === 'ruangan') {
            $ruangan = \App\Models\Ruangan::find($id);
            if (!$ruangan || $ruangan->status_ruangan === 'maintenance' || $ruangan->status_ruangan === 'tidak tersedia') {
                return back()->withErrors(['facility_id' => 'Fasilitas ini sedang dalam masa perawatan dan tidak dapat dipinjam.'])->withInput();
            }
        }

        DB::beginTransaction();
        try {
            $jam_mulai = $request->input('jam_mulai');
            $jam_selesai = $request->input('jam_selesai');
            $tanggal_pengajuan = $request->input('tanggal') . ' ' . $jam_mulai;
 
            $peminjaman = \App\Models\Peminjaman::create([
                'user_id' => auth()->id(),
                'dosen_id' => $request->input('dosen_id'),
                'pic_id' => $request->input('pic_id'),
                'nama_kegiatan' => $request->input('nama_kegiatan'),
                'jumlah_peserta' => $request->input('jumlah_peserta'),
                'jenis_peminjaman' => strtolower($type) === 'ruangan' ? 'ruangan' : 'barang',
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'jam_mulai' => $jam_mulai,
                'jam_selesai' => $jam_selesai,
                'status' => 'menunggu_dosen',
                'keterangan' => $request->input('keterangan'),
            ]);
 
            if (strtolower($type) === 'ruangan') {
                DB::table('detail_peminjaman_ruangan')->insert([
                    'peminjaman_id' => $peminjaman->id_peminjaman,
                    'ruangan_id' => $id,
                ]);
            } else {
                DB::table('detail_peminjaman_barang')->insert([
                    'peminjaman_id' => $peminjaman->id_peminjaman,
                    'barang_id' => $id,
                    'jumlah' => $request->input('jumlah_barang') ?: 1,
                ]);
            }
 
            DB::commit();
 
            return redirect()->route('mahasiswa.dashboard')
                ->with('success', 'Pengajuan peminjaman berhasil diajukan dan sedang menunggu verifikasi.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Kamu tidak bisa melakukan peminjaman dikarenakan jadwal sudah dipinjam atau meminjam ruangan melebihi batas operasional.')->withInput();
        }
    }

    public function pengembalian()
    {
        if (!auth()->check() || !auth()->user()->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        $peminjaman = \App\Models\Peminjaman::with(['ruangan', 'barang'])
            ->where('user_id', auth()->id())
            ->where('status', 'siap_digunakan')
            ->get();

        return view('mahasiswa.pengembalian', compact('peminjaman'));
    }

    public function storePengembalian(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id_peminjaman',
            'kondisi' => 'required|in:baik,ada_catatan',
            'catatan' => 'nullable|string',
            'tanggal_selesai_aktual' => 'required|date',
            'jam_selesai_aktual' => 'required|date_format:H:i',
            'foto_kondisi' => 'required|file|image|mimes:jpeg,png,jpg,webp|max:10240',
            'dokumen_administrasi' => 'required|file|mimes:pdf|max:10240',
        ]);

        $peminjaman = \App\Models\Peminjaman::findOrFail($request->peminjaman_id);

        $fotoPath = null;
        if ($request->hasFile('foto_kondisi')) {
            $file = $request->file('foto_kondisi');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pengembalian/foto'), $fileName);
            $fotoPath = 'uploads/pengembalian/foto/' . $fileName;
        }

        $docPath = null;
        if ($request->hasFile('dokumen_administrasi')) {
            $file = $request->file('dokumen_administrasi');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pengembalian/dokumen'), $fileName);
            $docPath = 'uploads/pengembalian/dokumen/' . $fileName;
        }

        DB::beginTransaction();
        try {
            $peminjaman->update(['status' => 'proses_pengembalian']);

            if ($peminjaman->jenis_peminjaman === 'ruangan') {
                $pjmRuangan = $peminjaman->ruangan->first();
                $id_ruangan = $pjmRuangan ? $pjmRuangan->id_ruangan : null;

                $pengembalianId = DB::table('pengembalian_ruangan')->insertGetId([
                    'peminjaman_id' => $peminjaman->id_peminjaman,
                    'tanggal_pengembalian' => $request->input('tanggal_selesai_aktual'),
                    'jam_selesai_aktual' => $request->input('jam_selesai_aktual'),
                    'catatan' => $request->input('catatan'),
                    'foto_kondisi' => $fotoPath,
                    'dokumen_administrasi' => $docPath,
                    'status' => 'menunggu_pic',
                ]);

                if ($id_ruangan) {
                    DB::table('detail_pengembalian_ruangan')->insert([
                        'id_pengembalian_ruangan' => $pengembalianId,
                        'id_ruangan' => $id_ruangan,
                        'kondisi_ruangan' => $request->input('kondisi') === 'baik' ? 'baik' : 'rusak ringan',
                        'catatan' => $request->input('catatan'),
                    ]);
                }
            } else {
                $pjmBarang = $peminjaman->barang->first();
                $id_barang = $pjmBarang ? $pjmBarang->id_barang : null;
                $jumlah = $pjmBarang ? ($pjmBarang->pivot->jumlah ?? 1) : 1;

                $pengembalianId = DB::table('pengembalian_barang')->insertGetId([
                    'peminjaman_id' => $peminjaman->id_peminjaman,
                    'tanggal' => $request->input('tanggal_selesai_aktual'),
                    'jam_selesai_aktual' => $request->input('jam_selesai_aktual'),
                    'catatan' => $request->input('catatan'),
                    'foto_kondisi' => $fotoPath,
                    'dokumen_administrasi' => $docPath,
                    'status' => 'menunggu_pic',
                ]);

                if ($id_barang) {
                    DB::table('detail_pengembalian_barang')->insert([
                        'id_pengembalian_barang' => $pengembalianId,
                        'id_barang' => $id_barang,
                        'jumlah_barang_dikembalikan' => $jumlah,
                        'kondisi_barang' => $request->input('kondisi') === 'baik' ? 'baik' : 'rusak ringan',
                        'catatan' => $request->input('catatan'),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('mahasiswa.dashboard')
                ->with('success', 'Pengembalian berhasil diajukan dan sedang menunggu verifikasi.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage())->withInput();
        }
    }

    public function profil()
    {
        if (!auth()->check() || !auth()->user()->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        return view('mahasiswa.profil');
    }

    public function notifikasi()
    {
        if (!auth()->check() || !auth()->user()->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        $notifikasi = \App\Models\Peminjaman::with(['ruangan', 'barang'])
            ->where('user_id', auth()->id())
            ->orderBy('id_peminjaman', 'desc')
            ->take(20)
            ->get();

        $belumDibaca = $notifikasi->where('tanggal_pengajuan', '>=', now()->subDays(2)->toDateString())->count();
        $hariIni = $notifikasi->where('tanggal_pengajuan', '>=', now()->toDateString())->count();

        $persetujuan = $notifikasi->whereIn('status', ['disetujui', 'ditolak'])->count();
        $jadwal = $notifikasi->where('status', 'disetujui')->where('tanggal_pengajuan', '>=', now()->toDateString())->count();

        return view('mahasiswa.notifikasi', compact('notifikasi', 'belumDibaca', 'hariIni', 'persetujuan', 'jadwal'));
    }

    public function riwayat()
    {
        if (!auth()->check() || !auth()->user()->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        $peminjaman = \App\Models\Peminjaman::with(['ruangan', 'barang', 'dosen'])
            ->where('user_id', auth()->id())
            ->orderBy('id_peminjaman', 'desc')
            ->get();

        return view('mahasiswa.riwayat', compact('peminjaman'));
    }

    public function eksporPdf($id)
    {
        if (!auth()->check() || !auth()->user()->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        $peminjaman = \App\Models\Peminjaman::with(['user', 'ruangan', 'barang', 'dosen', 'verifikasi.verifikator'])
            ->where('user_id', auth()->id())
            ->where('id_peminjaman', $id)
            ->firstOrFail();

        // Load view into PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('mahasiswa.pdf_bukti', compact('peminjaman'));
        
        return $pdf->download('Bukti_Peminjaman_' . $peminjaman->id_peminjaman . '.pdf');
    }

    public function eksporPdfPengembalian($id)
    {
        if (!auth()->check() || !auth()->user()->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        $peminjaman = \App\Models\Peminjaman::with(['user', 'ruangan', 'barang', 'pengembalian'])
            ->where('user_id', auth()->id())
            ->where('id_peminjaman', $id)
            ->firstOrFail();
            
        if (!$peminjaman->pengembalian) {
            abort(404, 'Data pengembalian belum ada.');
        }

        // Load view into PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('mahasiswa.pdf_bukti_pengembalian', compact('peminjaman'));
        
        return $pdf->download('Bukti_Pengembalian_' . $peminjaman->id_peminjaman . '.pdf');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
