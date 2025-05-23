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
        return $this->hasMany(DosenModel::class, 'kategori_id', 'kategori_id');
    }

    public function lombas()
    {
        return $this->belongsToMany(LombaModel::class, 't_lomba_kategori', 'kategori_id', 'lomba_id');
    }
}
