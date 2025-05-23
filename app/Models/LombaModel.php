<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LombaModel extends Model
{
    use HasFactory;

    protected $table = 't_lomba';
    protected $primaryKey = 'lomba_id';

    protected $fillable = [
        // 'kategori_id',
        'tingkat_lomba_id',
        'nama_lomba',
        'deskripsi_lomba',
        'penyelenggara_lomba',
        'awal_registrasi_lomba',
        'akhir_registrasi_lomba',
        'link_pendaftaran_lomba',
        'img_lomba',
        'status_verifikasi',
        'status_lomba',
    ];

    public function kategori(): BelongsToMany
    {
        // return $this->belongsTo(KategoriModel::class, 'kategori_id','kategori_id');
        return $this->belongsToMany(KategoriModel::class, 't_kategori_lomba', 'lomba_id', 'kategori_id');
    }

    public function tingkat_lomba(): BelongsTo
    {
        return $this->belongsTo(TingkatLombaModel::class, 'tingkat_lomba_id', 'tingkat_lomba_id');
    }
}
