<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikasiPeminjaman extends Model
{
    protected $table = 'verifikasi_peminjaman';
    protected $primaryKey = 'id_verifikasi_peminjaman';
    public $timestamps = false;

    protected $fillable = [
        'id_peminjaman',
        'id_verifikator',
        'peran_verifikasi',
        'jenis_verifikasi',
        'status',
        'catatan',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'id_verifikator', 'id_user');
    }
}
