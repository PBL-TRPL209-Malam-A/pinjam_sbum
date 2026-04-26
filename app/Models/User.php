<?php

namespace App\Models;

<<<<<<< HEAD
=======
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> 4dea1910bb6bdff32fc71724c337cfa2a72847e4
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
<<<<<<< HEAD
    use Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'nama_lengkap',
        'nim',
        'nik',
=======
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
>>>>>>> 4dea1910bb6bdff32fc71724c337cfa2a72847e4
        'email',
        'password',
    ];

<<<<<<< HEAD
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

    public function isMahasiswa(): bool
    {
        return $this->roles()->where('role.id_role', 1)->exists();
=======
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
>>>>>>> 4dea1910bb6bdff32fc71724c337cfa2a72847e4
    }
}
