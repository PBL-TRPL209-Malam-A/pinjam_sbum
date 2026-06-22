<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjamanBarang extends Model
{
    protected $table = 'detail_peminjaman_barang';
    protected $primaryKey = 'id_detail_peminjaman_barang';
    public $timestamps = false;

    protected $fillable = [
        'peminjaman_id',
        'barang_id',
        'jumlah',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'id_peminjaman');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id_barang');
    }
}
