<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'dosen_id',
        'nama_kegiatan',
        'jumlah_peserta',
        'jenis_peminjaman',
        'tanggal_pengajuan',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
    ];

    // Accessors for backward compatibility with older views
    public function getNamaFasilitasAttribute()
    {
        $ruanganNames = $this->ruangan->pluck('nama_ruangan')->toArray();
        $barangNames = $this->barang->pluck('nama_barang')->toArray();
        $all = array_merge($ruanganNames, $barangNames);
        return count($all) > 0 ? implode(', ', $all) : '-';
    }

    public function getTanggalMulaiAttribute()
    {
        return $this->tanggal_pengajuan ? $this->tanggal_pengajuan->format('Y-m-d H:i') : '-';
    }

    public function getTanggalSelesaiAttribute()
    {
        return $this->tanggal_pengajuan ? $this->tanggal_pengajuan->format('Y-m-d H:i') : '-';
    }

    public function getTujuanPeminjamanAttribute()
    {
        return $this->nama_kegiatan . ($this->keterangan ? ' - ' . $this->keterangan : '');
    }

    public function getStatusPengajuanAttribute()
    {
        return $this->status;
    }

    public function setStatusPengajuanAttribute($value)
    {
        // Map old status names to new database status values if necessary
        $mapped = $value;
        if ($value === 'verif_dosen' || $value === 'verif_admin' || $value === 'disetujui_kepala') {
            $mapped = 'disetujui'; // or keep mapped logic if desired, but SQL status enum is: pending, disetujui, ditolak, selesai, dibatalkan
        }
        $this->attributes['status'] = $mapped;
    }

    public function getFasilitasAttribute()
    {
        return (object)[
            'nama_fasilitas' => $this->nama_fasilitas
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id', 'id_user');
    }

    public function detailRuangan()
    {
        return $this->hasMany(DetailPeminjamanRuangan::class, 'peminjaman_id', 'id_peminjaman');
    }

    public function detailBarang()
    {
        return $this->hasMany(DetailPeminjamanBarang::class, 'peminjaman_id', 'id_peminjaman');
    }

    public function ruangan()
    {
        return $this->belongsToMany(
            Ruangan::class,
            'detail_peminjaman_ruangan',
            'peminjaman_id',
            'ruangan_id',
            'id_peminjaman',
            'id_ruangan'
        );
    }

    public function barang()
    {
        return $this->belongsToMany(
            Barang::class,
            'detail_peminjaman_barang',
            'peminjaman_id',
            'barang_id',
            'id_peminjaman',
            'id_barang'
        )->withPivot('jumlah');
    }

    public function pengembalianRuangan()
    {
        return $this->hasOne(PengembalianRuangan::class, 'peminjaman_id', 'id_peminjaman');
    }

    public function pengembalianBarang()
    {
        return $this->hasOne(PengembalianBarang::class, 'peminjaman_id', 'id_peminjaman');
    }

    public function verifikasi()
    {
        return $this->hasMany(VerifikasiPeminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }
}
