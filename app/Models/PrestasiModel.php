<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrestasiModel extends Model
{
    use HasFactory;

    protected $table = 't_prestasi'; 
    protected $primaryKey = 'prestasi_id';  

    protected $fillable = [
        'mahasiswa_id', 
        'lomba_id', 
        'dosen_id', 
        'kategori_id', 
        'periode_id',
        'tanggal_prestasi', 
        'juara_prestasi',
        'jenis_prestasi',
        'img_kegiatan',
        'bukti_prestasi',
        'surat_tugas_prestasi',
        'status_prestasi',
        'status_verifikasi'
    ];

    public function mahasiswa(): BelongsTo {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id','mahasiswa_id');
    }

    public function lomba(): BelongsTo {
        return $this->belongsTo(LombaModel::class, 'lomba_id','lomba_id');
    }

    public function dosen(): BelongsTo {
        return $this->belongsTo(DosenModel::class, 'dosen_id','dosen_id');
    }

    public function kategori(): BelongsTo {
        return $this->belongsTo(KategoriModel::class, 'kategori_id','kategori_id');
    }

    public function periode(): BelongsTo {
        return $this->belongsTo(PeriodeModel::class, 'periode_id','periode_id');
    }
}
