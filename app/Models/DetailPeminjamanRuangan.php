<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjamanRuangan extends Model
{
    protected $table = 'detail_peminjaman_ruangan';
    protected $primaryKey = 'id_detail_peminjaman_ruangan';
    public $timestamps = false;

    protected $fillable = [
        'peminjaman_id',
        'ruangan_id',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'id_peminjaman');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id', 'id_ruangan');
    }
}
