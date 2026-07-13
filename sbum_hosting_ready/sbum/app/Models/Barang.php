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
        'pic_id',
    ];

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id', 'id_user');
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where(function($sub) use ($search) {
                $sub->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        });
    }

    public function scopeFilterByLocation($query, $location)
    {
        return $query->when($location && $location !== 'Semua Gedung', function ($q) use ($location) {
            $q->where('keterangan', 'like', "%{$location}%");
        });
    }

    public function scopeFilterByStatus($query, $status)
    {
        return $query->when($status && $status !== 'Semua', function ($q) use ($status) {
            if ($status === 'tersedia') {
                $q->where('stok_tersedia', '>', 5);
            } elseif ($status === 'terbatas') {
                $q->whereBetween('stok_tersedia', [1, 5]);
            } elseif ($status === 'tidak tersedia' || $status === 'habis') {
                $q->where('stok_tersedia', 0);
            }
        });
    }
}
