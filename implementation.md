
# Rencana Implementasi - Refaktor Modul Kelola Data Fasilitas (Ruangan)

Rencana ini merinci langkah-langkah implementasi untuk mewajibkan input manual `kode_ruangan` dan menerapkan Intelligent Syncing pada relasi fasilitas pendukung secara atomik.

## Rencana Perubahan

### 1. Komponen View (`resources/views/admin/fasilitas/index.blade.php`)
- **Tabel Utama**: Menampilkan kolom `Kode` (sudah ada, akan dipastikan kelengkapannya).
- **Modal Tambah Fasilitas**: Menambahkan atribut `required` pada input field `kode_ruangan` dan menambahkan label penanda wajib diisi (`*`).
- **Modal Ubah Fasilitas**: Menambahkan atribut `required` pada input field `kode_ruangan` dan menambahkan label penanda wajib diisi (`*`).

### 2. Komponen Backend (`app/Http/Controllers/AdminController.php`)
- **Method `fasilitasStore`**:
  - Mengubah aturan validasi `kode_ruangan` dari `nullable` menjadi `required|string|max:50|unique:ruangan,kode_ruangan`.
  - Menghapus fallback generator acak (`'kode_ruangan' => $request->kode_ruangan ?: 'RNG_' . uniqid()`) dan menyimpannya langsung dari `$request->kode_ruangan`.
- **Method `fasilitasUpdate`**:
  - Mengubah aturan validasi `kode_ruangan` dari `nullable` menjadi `required|string|max:50|unique:ruangan,kode_ruangan,' . $id . ',id_ruangan`.
  - Menggunakan `$request->kode_ruangan` secara langsung (menghapus operator fallback `?:`).
  - Memastikan tidak ada bug pemanggilan variabel `$validated` yang tidak terdefinisi.
  - Memastikan proses sinkronisasi relasi `fasilitas_items` (Intelligent Syncing) menggunakan `whereNotIn` dan terbungkus dalam `DB::beginTransaction()`, `DB::commit()`, dan `DB::rollBack()` untuk menjaga integritas data.

### 3. Komponen Pengujian (`tests/Feature/FasilitasTest.php`)
- Memperbarui file pengujian untuk memastikan skenario penambahan dan pembaruan ruangan dengan manual `kode_ruangan` berjalan dengan sukses dan memicu error validasi jika dikirim kosong.

## Rencana Verifikasi
- Menjalankan unit/feature test:
  ```powershell
  $env:DB_CONNECTION="mysql"; $env:DB_DATABASE="db_pinjam_sbum"; $env:DB_USERNAME="root"; $env:DB_PASSWORD=""; php artisan test --filter=FasilitasTest
  ```
- Melakukan verifikasi manual melalui antarmuka web.
