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
        'status_notifikasi',
    ];

    public function user()
    {
        return $this->belongsTo(UsersModel::class, 'user_id', 'user_id');
    }
}
