<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DosenModel extends Model
{
    use HasFactory;

    protected $table = 't_dosen';
    protected $primaryKey = 'dosen_id';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'nip_dosen',
        'nama_dosen',
        'img_dosen',
        'alamat',
        'email',
        'no_hp',
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(UsersModel::class, 'user_id', 'user_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }
}