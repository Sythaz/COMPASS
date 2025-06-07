<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreferensiUserModel extends Model
{
    use HasFactory;

    protected $table = 't_preferensi_user';
    protected $primaryKey = 'preferensi_user_id';

    protected $fillable = [
        'user_id',
        'mahasiswa_id',
        'kriteria',
        'nilai',
        'prioritas',
    ];

    public function user()
    {
        return $this->belongsTo(UsersModel::class, 'user_id', 'user_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }
}
