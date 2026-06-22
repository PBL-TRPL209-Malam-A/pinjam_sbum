<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangan';
    protected $primaryKey = 'id_ruangan';
    public $timestamps = false;

    protected $fillable = [
        'nama_ruangan',
        'nama_gedung',
        'kode_ruangan',
        'kapasitas',
        'lantai',
        'status_ruangan',
        'foto_ruangan',
        'deskripsi_ruangan',
        'id_pic',
    ];

    public function pic()
    {
        return $this->belongsTo(User::class, 'id_pic', 'id_user');
    }

    public function fasilitas()
    {
        return $this->hasMany(FasilitasRuangan::class, 'id_ruangan', 'id_ruangan');
    }
}
