<?php

use App\Http\Controllers\AuthPeminjamController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'index')->name('home');

Route::get('/login', [AuthPeminjamController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthPeminjamController::class, 'showRegister'])->name('register');

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthPeminjamController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthPeminjamController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/peminjam/dashboard', [AuthPeminjamController::class, 'dashboard'])->name('peminjam.dashboard');
    Route::get('/peminjam/fasilitas', [AuthPeminjamController::class, 'fasilitas'])->name('peminjam.fasilitas');
    Route::get('/peminjam/fasilitas/detail', [AuthPeminjamController::class, 'fasilitasDetail'])->name('peminjam.fasilitas.detail');
    Route::get('/peminjam/jadwal', [AuthPeminjamController::class, 'jadwal'])->name('peminjam.jadwal');
    Route::get('/peminjam/jadwal/slots', [\App\Http\Controllers\ScheduleController::class, 'getSlots'])->name('peminjam.jadwal.slots');
    Route::get('/peminjam/pengajuan', [AuthPeminjamController::class, 'pengajuan'])->name('peminjam.pengajuan');
    Route::post('/peminjam/pengajuan', [AuthPeminjamController::class, 'pengajuanStore'])->name('peminjam.pengajuan.store');
    Route::get('/peminjam/pengembalian', [AuthPeminjamController::class, 'pengembalian'])->name('peminjam.pengembalian');
    Route::post('/peminjam/pengembalian', [AuthPeminjamController::class, 'storePengembalian'])->name('peminjam.pengembalian.store');
    Route::get('/peminjam/profil', [AuthPeminjamController::class, 'profil'])->name('peminjam.profil');
    Route::get('/peminjam/notifikasi', [AuthPeminjamController::class, 'notifikasi'])->name('peminjam.notifikasi');
    Route::get('/peminjam/riwayat', [AuthPeminjamController::class, 'riwayat'])->name('peminjam.riwayat');
    Route::get('/peminjam/riwayat/pdf/{id}', [AuthPeminjamController::class, 'eksporPdf'])->name('peminjam.riwayat.pdf');
    Route::get('/peminjam/riwayat/pdf-pengembalian/{id}', [AuthPeminjamController::class, 'eksporPdfPengembalian'])->name('peminjam.riwayat.pdf_pengembalian');
    Route::post('/logout', [AuthPeminjamController::class, 'logout'])->name('logout');
});

// Routes for Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin/fasilitas', [\App\Http\Controllers\AdminController::class, 'fasilitasIndex'])->name('admin.fasilitas');
    Route::post('/admin/fasilitas', [\App\Http\Controllers\AdminController::class, 'fasilitasStore'])->name('admin.fasilitas.store');
    Route::put('/admin/fasilitas/{id}', [\App\Http\Controllers\AdminController::class, 'fasilitasUpdate'])->name('admin.fasilitas.update');
    Route::delete('/admin/fasilitas/{id}', [\App\Http\Controllers\AdminController::class, 'fasilitasDestroy'])->name('admin.fasilitas.destroy');

    Route::get('/admin/inventaris', [\App\Http\Controllers\AdminController::class, 'inventarisIndex'])->name('admin.inventaris');
    Route::post('/admin/inventaris', [\App\Http\Controllers\AdminController::class, 'inventarisStore'])->name('admin.inventaris.store');
    Route::put('/admin/inventaris/{id}', [\App\Http\Controllers\AdminController::class, 'inventarisUpdate'])->name('admin.inventaris.update');
    Route::delete('/admin/inventaris/{id}', [\App\Http\Controllers\AdminController::class, 'inventarisDestroy'])->name('admin.inventaris.destroy');

    Route::get('/admin/verifikasi-peminjaman', [\App\Http\Controllers\AdminController::class, 'verifikasiPeminjamanIndex'])->name('admin.verifikasi-peminjaman');
    Route::put('/admin/verifikasi-peminjaman/{id}', [\App\Http\Controllers\AdminController::class, 'verifikasiPeminjamanStore'])->name('admin.verifikasi-peminjaman.verifikasi');

    Route::get('/admin/jadwal', [\App\Http\Controllers\AdminController::class, 'jadwalIndex'])->name('admin.jadwal');
    Route::post('/admin/jadwal', [\App\Http\Controllers\AdminController::class, 'jadwalStore'])->name('admin.jadwal.store');
    Route::get('/admin/jadwal/api-slots', [\App\Http\Controllers\AdminController::class, 'apiGetSlots'])->name('admin.jadwal.api-slots');
    Route::post('/admin/jadwal/api-save', [\App\Http\Controllers\AdminController::class, 'apiSaveSlots'])->name('admin.jadwal.api-save');

    Route::get('/admin/peminjaman', [\App\Http\Controllers\AdminController::class, 'peminjamanIndex'])->name('admin.peminjaman');
    Route::put('/admin/peminjaman/{id}', [\App\Http\Controllers\AdminController::class, 'peminjamanVerifikasi'])->name('admin.peminjaman.verifikasi');

    Route::get('/admin/pengembalian', [\App\Http\Controllers\AdminController::class, 'pengembalianIndex'])->name('admin.pengembalian');
    Route::put('/admin/pengembalian/{id}', [\App\Http\Controllers\AdminController::class, 'pengembalianVerifikasi'])->name('admin.pengembalian.verifikasi');

    Route::get('/admin/verifikasi-pengembalian', [\App\Http\Controllers\AdminController::class, 'verifikasiPengembalianIndex'])->name('admin.verifikasi-pengembalian');
    Route::put('/admin/verifikasi-pengembalian/{id}', [\App\Http\Controllers\AdminController::class, 'verifikasiPengembalianStore'])->name('admin.verifikasi-pengembalian.verifikasi');

    Route::get('/admin/profil', [\App\Http\Controllers\AdminController::class, 'profilIndex'])->name('admin.profil');
    Route::put('/admin/profil', [\App\Http\Controllers\AdminController::class, 'profilUpdate'])->name('admin.profil.update');
});

// Routes for Dosen
Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dosen/dashboard', [\App\Http\Controllers\DosenController::class, 'dashboard'])->name('dosen.dashboard');
    Route::get('/dosen/verifikasi-peminjaman', [\App\Http\Controllers\DosenController::class, 'verifikasiPeminjamanIndex'])->name('dosen.verifikasi-peminjaman');
    Route::put('/dosen/verifikasi/{id}', [\App\Http\Controllers\DosenController::class, 'verifikasi'])->name('dosen.verifikasi');
    Route::get('/dosen/profil', [\App\Http\Controllers\DosenController::class, 'profilIndex'])->name('dosen.profil');
    Route::put('/dosen/profil', [\App\Http\Controllers\DosenController::class, 'profilUpdate'])->name('dosen.profil.update');
});

// Routes for Kepala SBUM
Route::middleware(['auth', 'role:kepalasbum'])->group(function () {
    Route::get('/kepalasbum/dashboard', [\App\Http\Controllers\KepalaSbumController::class, 'dashboard'])->name('kepalasbum.dashboard');
    Route::get('/kepalasbum/staff', [\App\Http\Controllers\KepalaSbumController::class, 'staffIndex'])->name('kepalasbum.staff');
    Route::post('/kepalasbum/staff', [\App\Http\Controllers\KepalaSbumController::class, 'staffStore'])->name('kepalasbum.staff.store');
    Route::put('/kepalasbum/staff/{id}', [\App\Http\Controllers\KepalaSbumController::class, 'staffUpdate'])->name('kepalasbum.staff.update');
    Route::delete('/kepalasbum/staff/{id}', [\App\Http\Controllers\KepalaSbumController::class, 'staffDestroy'])->name('kepalasbum.staff.destroy');
    Route::get('/kepalasbum/persetujuan', [\App\Http\Controllers\KepalaSbumController::class, 'persetujuanIndex'])->name('kepalasbum.persetujuan');
        Route::post('/kepalasbum/persetujuan/setujui-semua', [\App\Http\Controllers\KepalaSbumController::class, 'verifikasiSemua'])->name('kepalasbum.verifikasi-semua');
    Route::put('/kepalasbum/persetujuan/{id}', [\App\Http\Controllers\KepalaSbumController::class, 'verifikasi'])->name('kepalasbum.verifikasi');
    Route::get('/kepalasbum/laporan', [\App\Http\Controllers\KepalaSbumController::class, 'laporanIndex'])->name('kepalasbum.laporan');
    Route::get('/kepalasbum/laporan/export-excel', [\App\Http\Controllers\KepalaSbumController::class, 'exportPeminjamanExcel'])->name('kepalasbum.laporan.export');
    Route::get('/kepalasbum/laporan-pengembalian', [\App\Http\Controllers\KepalaSbumController::class, 'laporanPengembalian'])->name('kepalasbum.laporan-pengembalian');
    Route::get('/kepalasbum/laporan-pengembalian/export-excel', [\App\Http\Controllers\KepalaSbumController::class, 'exportPengembalianExcel'])->name('kepalasbum.laporan-pengembalian.export');
    Route::get('/kepalasbum/profil', [\App\Http\Controllers\KepalaSbumController::class, 'profil'])->name('kepalasbum.profil');
});

// Routes for PIC
Route::middleware(['auth', 'role:pic'])->group(function () {
    Route::get('/pic/dashboard', [\App\Http\Controllers\PicController::class, 'dashboard'])->name('pic.dashboard');
    Route::get('/pic/kesiapan', [\App\Http\Controllers\PicController::class, 'kesiapanIndex'])->name('pic.kesiapan');
    Route::post('/pic/kesiapan', [\App\Http\Controllers\PicController::class, 'kesiapanStore'])->name('pic.kesiapan.store');
    Route::get('/pic/pengembalian', [\App\Http\Controllers\PicController::class, 'konfirmasiPengembalianIndex'])->name('pic.pengembalian');
    Route::post('/pic/pengembalian', [\App\Http\Controllers\PicController::class, 'konfirmasiPengembalianStore'])->name('pic.pengembalian.store');
    Route::get('/pic/profil', [\App\Http\Controllers\PicController::class, 'profilIndex'])->name('pic.profil');
    Route::put('/pic/profil', [\App\Http\Controllers\PicController::class, 'profilUpdate'])->name('pic.profil.update');
});

// Routes for Pamdal
Route::middleware(['auth', 'role:pamdal'])->group(function () {
    Route::get('/pamdal/dashboard', [\App\Http\Controllers\PamdalController::class, 'dashboard'])->name('pamdal.dashboard');
    Route::get('/pamdal/monitoring', [\App\Http\Controllers\PamdalController::class, 'pengawasanIndex'])->name('pamdal.monitoring');
    Route::post('/pamdal/monitoring', [\App\Http\Controllers\PamdalController::class, 'pengawasanStore'])->name('pamdal.monitoring.store');
    Route::get('/pamdal/profil', [\App\Http\Controllers\PamdalController::class, 'profilIndex'])->name('pamdal.profil');
    Route::put('/pamdal/profil', [\App\Http\Controllers\PamdalController::class, 'profilUpdate'])->name('pamdal.profil.update');
});
