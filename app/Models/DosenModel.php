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
        'nip_dosen',
        'nama_dosen',
        'img_dosen',
        'kelamin',
        'status',
        'alamat',
        'email',
        'no_hp',
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(
            UsersModel::class,
            'user_id',
            'user_id'
        );
    }

    public function kategoris()
    {
        return $this->belongsToMany(
            KategoriModel::class,
            't_kategori_dosen',
            'dosen_id',
            'kategori_id'
        );
    }

    public function preferensiDosen()
    {
        return $this->hasMany(PreferensiDosenModel::class, 'dosen_id', 'dosen_id');
    }

}