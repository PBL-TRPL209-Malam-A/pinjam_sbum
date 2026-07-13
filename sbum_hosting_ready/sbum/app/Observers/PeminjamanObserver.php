<?php

namespace App\Observers;

use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class PeminjamanObserver
{
    /**
     * Handle the Peminjaman "updated" event.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return void
     */
    public function updated(Peminjaman $peminjaman)
    {
        // Check if the status has changed
        if ($peminjaman->isDirty('status')) {
            $oldStatus = $peminjaman->getOriginal('status');
            $newStatus = $peminjaman->status;

            // Define statuses that signify the items are returned or booking failed
            $returnedStatuses = ['ditolak', 'batal', 'selesai'];
            
            // If the status changes from something else TO a returned status
            if (in_array($newStatus, $returnedStatuses) && !in_array($oldStatus, $returnedStatuses)) {
                // Return stock if it's a barang booking
                if ($peminjaman->jenis_peminjaman === 'barang') {
                    // Get the detail mapping to find how many items were borrowed
                    $detail = DB::table('detail_peminjaman_barang')
                                ->where('peminjaman_id', $peminjaman->id_peminjaman)
                                ->first();
                                
                    if ($detail) {
                        $barangId = $detail->barang_id;
                        $jumlah = $detail->jumlah;

                        if ($barangId && $jumlah > 0) {
                            // Restore stock inside a transaction with lock
                            DB::transaction(function () use ($barangId, $jumlah) {
                                $barang = Barang::where('id_barang', $barangId)->lockForUpdate()->first();
                                if ($barang) {
                                    $barang->stok_tersedia += $jumlah;
                                    $barang->save();
                                }
                            });
                        }
                    }
                }
            }
        }
    }
}
