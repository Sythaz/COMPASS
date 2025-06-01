<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class PrestasiModel extends Model
{
    use HasFactory;

    protected $table = 't_prestasi';
    protected $primaryKey = 'prestasi_id';

    protected $fillable = [
        'mahasiswa_id',
        'lomba_id',
        'dosen_id',
        'periode_id',
        'tanggal_prestasi',
        'juara_prestasi',
        'jenis_prestasi',
        'img_kegiatan',
        'bukti_prestasi',
        'surat_tugas_prestasi',
        'status_prestasi',
        'status_verifikasi',
    ];

    public function mahasiswa(): BelongsToMany
    {
        return $this->belongsToMany(
            MahasiswaModel::class,
            't_prestasi_mahasiswa',
            'prestasi_id',
            'mahasiswa_id'
        )->withPivot('peran')->withTimestamps();
    }

    public function lomba(): BelongsTo
    {
        return $this->belongsTo(
            LombaModel::class,
            'lomba_id',
            'lomba_id'
        );
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(
            DosenModel::class,
            'dosen_id',
            'dosen_id'
        );
    }

    public function kategori(): BelongsToMany
    {
        return $this->belongsToMany(
            KategoriModel::class,
            't_kategori_prestasi',
            'prestasi_id',
            'kategori_id'
        );
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(
            PeriodeModel::class,
            'periode_id',
            'periode_id'
        );
    }

}
