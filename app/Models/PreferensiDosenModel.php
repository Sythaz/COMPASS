<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreferensiDosenModel extends Model
{
    use HasFactory;

    protected $table = 't_preferensi_dosen';
    protected $primaryKey = 'preferensi_dosen_id';

    protected $fillable = [
        'user_id',
        'dosen_id',
        'kriteria',
        'nama',
        'prioritas',
    ];

    public function user()
    {
        return $this->belongsTo(UsersModel::class, 'user_id', 'user_id');
    }

    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id', 'dosen_id');
    }
}
