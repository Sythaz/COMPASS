<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UsersModel extends Authenticatable
{
    use HasFactory;

    protected $table = 't_users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'password',
        'phrase',
        'role',
    ];

    protected $hidden = [
        'password', // menyembunyikan password saat query
    ];

    protected $casts = [
        'password' => 'hashed', // otomatis hash saat isi atau ubah password
    ];

    /**
     * Mendapatkan nama pengguna
     */
    public function getName(): string
    {
        // ambil data nama_admin dari database t_admin model AdminModel
        $admin = AdminModel::find($this->user_id);

        return $admin->nama_admin;
    }

    public function getProfile(): string
    {
        // ambil data img_admin dari database t_admin model AdminModel
        $admin = AdminModel::find($this->user_id);
        return $admin->img_admin;
    }

    /**
     * Mendapatkan nama role pengguna
     */
    public function getRoleName(): string
    {
        return $this->role; // disesuaikan dengan field 'role' yang kamu punya
    }

    /**
     * Mengecek apakah user memiliki role tertentu
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Mendapatkan role user (kode atau nama)
     */
    public function getRole(): string
    {
        return $this->role;
    }
}
