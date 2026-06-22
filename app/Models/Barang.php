<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $timestamps = false;

    protected $fillable = [
        'nama_barang',
        'kode_barang',
        'stok_tersedia',
        'foto_barang',
        'keterangan',
        'id_pic',
    ];

    public function pic()
    {
        return $this->belongsTo(User::class, 'id_pic', 'id_user');
    }
}
