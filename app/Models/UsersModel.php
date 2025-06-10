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
        $nama = 'User';

        switch ($this->role) {
            case 'Admin':
                $admin = \App\Models\AdminModel::where('user_id', $this->user_id)->first();
                $nama = $admin?->nama_admin ?? 'Admin';
                break;

            case 'Dosen':
                $dosen = \App\Models\DosenModel::where('user_id', $this->user_id)->first();
                $nama = $dosen?->nama_dosen ?? 'Dosen';
                break;

            case 'Mahasiswa':
                $mhs = \App\Models\MahasiswaModel::where('user_id', $this->user_id)->first();
                $nama = $mhs?->nama_mahasiswa ?? 'Mahasiswa';
                break;
        }

        return $nama;
    }

    public function getProfile(): string
    {
        $img = 'default-profile.png';

        switch (strtolower($this->role)) {
            case 'admin':
                $img = AdminModel::where('user_id', $this->user_id)->first()?->img_admin ?? $img;
                break;
            case 'dosen':
                $img = DosenModel::where('user_id', $this->user_id)->first()?->img_dosen ?? $img;
                break;
            case 'mahasiswa':
                $img = MahasiswaModel::where('user_id', $this->user_id)->first()?->img_mahasiswa ?? $img;
                break;
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
