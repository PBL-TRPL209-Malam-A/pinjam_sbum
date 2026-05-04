<?php

use App\Http\Controllers\AuthMahasiswaController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'index')->name('home');

Route::get('/login', [AuthMahasiswaController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthMahasiswaController::class, 'showRegister'])->name('register');

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthMahasiswaController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthMahasiswaController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/mahasiswa/dashboard', [AuthMahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');
    Route::get('/mahasiswa/fasilitas', [AuthMahasiswaController::class, 'fasilitas'])->name('mahasiswa.fasilitas');
    Route::get('/mahasiswa/jadwal', [AuthMahasiswaController::class, 'jadwal'])->name('mahasiswa.jadwal');
    Route::get('/mahasiswa/pengajuan', [AuthMahasiswaController::class, 'pengajuan'])->name('mahasiswa.pengajuan');
    Route::get('/mahasiswa/pengembalian', [AuthMahasiswaController::class, 'pengembalian'])->name('mahasiswa.pengembalian');
    Route::get('/mahasiswa/profil', [AuthMahasiswaController::class, 'profil'])->name('mahasiswa.profil');
    Route::get('/mahasiswa/notifikasi', [AuthMahasiswaController::class, 'notifikasi'])->name('mahasiswa.notifikasi');
    Route::post('/logout', [AuthMahasiswaController::class, 'logout'])->name('logout');
});
