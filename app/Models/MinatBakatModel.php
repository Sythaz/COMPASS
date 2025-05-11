<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MinatBakatModel extends Model
{
    use HasFactory;

    protected $table = 't_minat_bakat'; 
    protected $primaryKey = 'minbak_id';  

    protected $fillable = [
        'mahasiswa_id', 
        'kategori_id', 
        'level_minbak',
    ];

    public function mahasiswa(): BelongsTo {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id','mahasiswa_id');
    }

    public function kategori(): BelongsTo {
        return $this->belongsTo(KategoriModel::class, 'kategori_id','kategori_id');
    }
}
