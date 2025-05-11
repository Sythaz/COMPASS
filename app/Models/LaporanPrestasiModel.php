<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanPrestasiModel extends Model
{
    use HasFactory;

    protected $table = 't_laporan_prestasi'; 
    protected $primaryKey = 'laporan_id';  

    protected $fillable = [
        'prestasi_id', 
        'mahasiswa_id', 
        'tanggal_laporan',
    ];

    public function prestasi(): BelongsTo {
        return $this->belongsTo(PrestasiModel::class, 'prestasi_id','prestasi_id');
    }

    public function mahasiswa(): BelongsTo {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id','mahasiswa_id');
    }
}
