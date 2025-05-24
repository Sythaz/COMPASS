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

    public function mahasiswa()
    {
        return $this->hasOne(MahasiswaModel::class, 'user_id', 'user_id');
    }

    public function dosen()
    {
        return $this->hasOne(DosenModel::class, 'user_id', 'user_id');
    }

    public function admin()
    {
        return $this->hasOne(AdminModel::class, 'user_id', 'user_id');
    }

    /**
     * Mendapatkan nama pengguna
     */
    public function getName(): string
    {
        $nama = '';
        // Ambil data nama_admin dari database sesuai role
        switch ($this->role) {
            case 'Admin':
                $nama = AdminModel::find($this->user_id)->nama_admin;
                break;
            case 'Dosen':
                $nama = DosenModel::find($this->user_id)->nama_dosen;
                break;
            case 'Mahasiswa':
                $nama = MahasiswaModel::find($this->user_id)->nama_mahasiswa;
                break;
            default:
                $nama = 'User';
        }

        return $nama;
    }

    public function getProfile(): string
    {
        $img = '';

        // Ambil data img dari database sesuai role
        switch ($this->role) {
            case 'admin':
                $img = AdminModel::find($this->user_id)->img_admin;
                break;
            case 'dosen':
                $img = DosenModel::find($this->user_id)->img_dosen;
                break;
            case 'mahasiswa':
                $img = MahasiswaModel::find($this->user_id)->img_mahasiswa;
                break;
            default:
                $img = 'default-profile.png';
        }
        return $img;
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
