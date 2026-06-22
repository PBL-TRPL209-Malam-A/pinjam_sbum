<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengembalianRuangan extends Model
{
    protected $table = 'pengembalian_ruangan';
    protected $primaryKey = 'id_pengembalian_ruangan';
    public $timestamps = false;

    protected $fillable = [
        'peminjaman_id',
        'tanggal_pengembalian',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pengembalian' => 'date',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'id_peminjaman');
    }

    public function detail()
    {
        return $this->hasMany(DetailPengembalianRuangan::class, 'id_pengembalian_ruangan', 'id_pengembalian_ruangan');
    }
}
