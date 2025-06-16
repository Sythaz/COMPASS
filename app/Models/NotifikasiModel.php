<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifikasiModel extends Model
{
    use HasFactory;
    protected $table = 't_notifikasi';
    protected $primaryKey = 'notifikasi_id';
    protected $fillable = [
        'user_id',
        'pengirim_id',
        'pengirim_role',
        'jenis_notifikasi',
        'pesan_notifikasi',
        'lomba_id',
        'prestasi_id',
        'pendaftaran_id',
        'status_notifikasi',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(UsersModel::class, 'user_id', 'user_id');
    }

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranLombaModel::class, 'pendaftaran_id', 'pendaftaran_id');
    }

    public function getPengirimNama()
    {
        if ($this->pengirim_role === 'Dosen') {
            $pengirim = DosenModel::where('user_id', $this->pengirim_id)->first();
            return $pengirim ? $pengirim->nama_dosen : 'Nama Dosen Tidak Ditemukan';
        } elseif ($this->pengirim_role === 'Admin') {
            $pengirim = AdminModel::where('user_id', $this->pengirim_id)->first();
            return $pengirim ? $pengirim->nama_admin : 'Nama Admin Tidak Ditemukan';
        } elseif ($this->pengirim_role === 'Sistem') {
            return 'Sistem';
        }
        return 'Pengirim Tidak Diketahui';
    }
}
