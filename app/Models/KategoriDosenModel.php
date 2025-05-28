<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriDosenModel extends Model
{
    use HasFactory;

    protected $table = 't_kategori_dosen';
    protected $primaryKey = 'kategori_dosen_id';  // sesuai migration

    protected $fillable = [
        'dosen_id',
        'kategori_id',
    ];

    // Relasi ke DosenModel
    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id', 'dosen_id');
    }

    // Relasi ke KategoriModel
    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }
}
