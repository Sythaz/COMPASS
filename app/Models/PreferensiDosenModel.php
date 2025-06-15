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
        'dosen_id',
        'kategori_id',
        'prioritas',
    ];

    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id', 'dosen_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }
}
