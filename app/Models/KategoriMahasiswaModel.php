<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriMahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 't_kategori_mahasiswa';
    protected $primaryKey = 'kategori_mahasiswa_id';

    protected $fillable = [
        'mahasiswa_id',
        'kategori_id',
    ];

    // Optional relasi ke Mahasiswa dan Kategori
    public function mahasiswa()
    {
        return $this->belongsTo(
            MahasiswaModel::class,
            'mahasiswa_id',
            'mahasiswa_id'
        );
    }

    public function kategori()
    {
        return $this->belongsTo(
            KategoriModel::class,
            'kategori_id',
            'kategori_id'
        );
    }
}
