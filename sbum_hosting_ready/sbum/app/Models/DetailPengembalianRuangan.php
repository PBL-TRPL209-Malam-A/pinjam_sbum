<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPengembalianRuangan extends Model
{
    protected $table = 'detail_pengembalian_ruangan';
    protected $primaryKey = 'id_detail_pengembalian_ruangan';
    public $timestamps = false;

    protected $fillable = [
        'id_pengembalian_ruangan',
        'id_ruangan',
        'kondisi_ruangan',
        'catatan',
    ];

    public function header()
    {
        return $this->belongsTo(PengembalianRuangan::class, 'id_pengembalian_ruangan', 'id_pengembalian_ruangan');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id_ruangan');
    }
}
