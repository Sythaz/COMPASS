<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreferensiUserModel extends Model
{
    use HasFactory;

    protected $table = 't_preferensi_user';
    protected $primaryKey = 'id_preferensi_user';

    protected $fillable = [
        'user_id',
        'kriteria',
        'nilai',
        'prioritas',
    ];

    public function user()
    {
        return $this->belongsTo(UsersModel::class, 'user_id', 'user_id');
    }
}
