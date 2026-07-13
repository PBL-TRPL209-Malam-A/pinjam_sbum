<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';
    public $timestamps = false;

    protected $fillable = [
        'ruangan_id',
        'barang_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status',
        'peminjaman_id',
    ];

    protected $casts = [
        'tanggal' => 'date:Y-m-d',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id', 'id_ruangan');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id_barang');
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'id_peminjaman');
    }
}
