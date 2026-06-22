<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengembalianBarang extends Model
{
    protected $table = 'pengembalian_barang';
    protected $primaryKey = 'id_pengembalian_barang';
    public $timestamps = false;

    protected $fillable = [
        'peminjaman_id',
        'tanggal',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'id_peminjaman');
    }

    public function detail()
    {
        return $this->hasMany(DetailPengembalianBarang::class, 'id_pengembalian_barang', 'id_pengembalian_barang');
    }
}
