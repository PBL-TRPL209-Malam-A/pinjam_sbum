<?php
$id = 1;
$kategori = 'barang';
$decision = 'disetujui';
$statusPengembalian = 'selesai';
$request = new \Illuminate\Http\Request(['catatan'=>'tes']);

try {
    $b = \App\Models\PengembalianBarang::findOrFail($id);
    $peminjaman = $b->peminjaman;

    \Illuminate\Support\Facades\DB::transaction(function() use ($peminjaman, $statusPengembalian, $kategori, $id, $request) {
        $peminjaman->pengembalianBarang->update(['status' => $statusPengembalian]);
        $peminjaman->update(['status' => $statusPengembalian]);

        \App\Models\VerifikasiPengembalian::create([
            'id_pengembalian_ruangan' => null,
            'id_pengembalian_barang' => $id,
            'id_verifikator' => 3,
            'peran_verifikasi' => 'Admin SBUM',
            'status' => 'disetujui',
            'catatan' => 'tes',
            'tanggal' => now()
        ]);
    });
    echo "SUCCESS\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
