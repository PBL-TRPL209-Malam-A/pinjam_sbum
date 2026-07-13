<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikasiPengembalian extends Model
{
    protected $table = 'verifikasi_pengembalian';
    protected $primaryKey = 'id_verifikasi_pengembalian';
    public $timestamps = false;

    protected $fillable = [
        'id_pengembalian_ruangan',
        'id_pengembalian_barang',
        'id_verifikator',
        'peran_verifikasi',
        'tanggal',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function pengembalianRuangan()
    {
        return $this->belongsTo(PengembalianRuangan::class, 'id_pengembalian_ruangan', 'id_pengembalian_ruangan');
    }

    public function pengembalianBarang()
    {
        return $this->belongsTo(PengembalianBarang::class, 'id_pengembalian_barang', 'id_pengembalian_barang');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'id_verifikator', 'id_user');
    }
}
