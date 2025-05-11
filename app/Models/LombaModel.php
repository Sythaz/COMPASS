<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LombaModel extends Model
{
    use HasFactory;

    protected $table = 't_lomba'; 
    protected $primaryKey = 'lomba_id';  

    protected $fillable = [
        'kategori_id', 
        'nama_lomba', 
        'tingkat_kompetisi_lomba',
        'deskripsi_lomba',
        'penyelenggara_lomba',
        'awal_registrasi_lomba',
        'akhir_registrasi_lomba',
        'link_pendaftaran_lomba',
        'img_lomba',
    ];

    public function kategori(): BelongsTo {
        return $this->belongsTo(KategoriModel::class, 'kategori_id','kategori_id');
    }
}
