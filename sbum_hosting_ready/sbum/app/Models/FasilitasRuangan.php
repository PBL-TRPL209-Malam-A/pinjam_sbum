<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FasilitasRuangan extends Model
{
    protected $table = 'fasilitas_ruangan';
    protected $primaryKey = 'id_fasilitas';
    public $timestamps = false;

    protected $fillable = [
        'id_ruangan',
        'nama_fasilitas',
        'jumlah',
        'keterangan',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id_ruangan');
    }
}
