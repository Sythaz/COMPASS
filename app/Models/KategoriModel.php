<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DosenModel; // Import ini wajib!
use App\Models\LombaModel; // Import ini wajib!

class KategoriModel extends Model
{
    use HasFactory;

    protected $table = 't_kategori';
    protected $primaryKey = 'kategori_id';

    protected $fillable = [
        'nama_kategori',
        'status_kategori',
    ];

    public function dosens()
    {
        return $this->belongsToMany(
            DosenModel::class,
            't_kategori_dosen',
            'kategori_id',
            'dosen_id'
        );
    }

    public function lombas()
    {
        return $this->belongsToMany(
            LombaModel::class,
            't_lomba_kategori',
            'kategori_id',
            'lomba_id'
        );
    }

    public function mahasiswas()
    {
        return $this->belongsToMany(
            MahasiswaModel::class,
            't_kategori_mahasiswa',
            'kategori_id',
            'mahasiswa_id'
        );
    }

}
