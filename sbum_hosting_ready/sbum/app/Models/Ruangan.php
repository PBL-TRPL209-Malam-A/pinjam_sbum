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
        'pic_id',
    ];
    //  tambahkan const gedung 
    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id', 'id_user');
    }

    public function fasilitas()
    {
        return $this->hasMany(FasilitasRuangan::class, 'id_ruangan', 'id_ruangan');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'ruangan_id', 'id_ruangan');
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where(function($sub) use ($search) {
                $sub->where('nama_ruangan', 'like', "%{$search}%")
                    ->orWhere('kode_ruangan', 'like', "%{$search}%");
            });
        });
    }

    public function scopeFilterByLocation($query, $location)
    {
        return $query->when($location && $location !== 'Semua Gedung', function ($q) use ($location) {
            $q->where('nama_gedung', $location);
        });
    }

    public function scopeFilterByStatus($query, $status)
    {
        return $query->when($status && $status !== 'Semua', function ($q) use ($status) {
            $q->where('status_ruangan', $status);
        });
    }

    public function scopeAvailable($query)
    {
        return $query->whereNotIn('status_ruangan', ['tidak tersedia', 'maintenance']);
    }
}
