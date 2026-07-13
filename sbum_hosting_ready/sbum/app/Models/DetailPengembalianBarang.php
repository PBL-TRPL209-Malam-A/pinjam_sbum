<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPengembalianBarang extends Model
{
    protected $table = 'detail_pengembalian_barang';
    protected $primaryKey = 'id_detail_pengembalian_barang';
    public $timestamps = false;

    protected $fillable = [
        'id_pengembalian_barang',
        'id_barang',
        'jumlah_barang_dikembalikan',
        'kondisi_barang',
        'catatan',
    ];

    public function header()
    {
        return $this->belongsTo(PengembalianBarang::class, 'id_pengembalian_barang', 'id_pengembalian_barang');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}
