<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'nama_lengkap',
        'nim',
        'nik',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'user_role',
            'user_id',
            'role_id',
            'id_user',
            'id_role'
        );
    }

    public function scopePenanggungJawab($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->whereIn('role.nama_role', ['Dosen', 'PIC Fasilitas'])
              ->orWhereIn('role.id_role', [2, 5]);
        });
    }

    public function isPeminjam(): bool
    {
        return $this->roles()->where('role.nama_role', 'Peminjam')->exists() || 
               $this->roles()->where('role.id_role', 1)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->roles()->where('role.nama_role', 'Admin SBUM')->exists() ||
               $this->roles()->where('role.id_role', 3)->exists();
    }

    public function isDosen(): bool
    {
        return $this->roles()->where('role.nama_role', 'Dosen')->exists() ||
               $this->roles()->where('role.id_role', 2)->exists();
    }

    public function isKepalaSbum(): bool
    {
        return $this->roles()->where('role.nama_role', 'Kepala SBUM')->exists() ||
               $this->roles()->where('role.id_role', 4)->exists();
    }

    public function isPic(): bool
    {
        return $this->roles()->where('role.nama_role', 'PIC Fasilitas')->exists() ||
               $this->roles()->where('role.id_role', 5)->exists();
    }

    public function isPamdal(): bool
    {
        return $this->roles()->where('role.nama_role', 'Pamdal')->exists() ||
               $this->roles()->where('role.id_role', 6)->exists();
    }
}
