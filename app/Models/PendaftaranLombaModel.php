<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PendaftaranLombaModel extends Model
{
    use HasFactory;

    protected $table = 't_pendaftaran_lomba';
    protected $primaryKey = 'pendaftaran_id';

    protected $fillable = [
        'mahasiswa_id', // ketua tim
        'lomba_id',
        'bukti_pendaftaran',
    ];

    /**
     * Relasi ke mahasiswa sebagai ketua tim
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    /**
     * Relasi ke lomba
     */
    public function lomba(): BelongsTo
    {
        return $this->belongsTo(LombaModel::class, 'lomba_id', 'lomba_id');
    }

    /**
     * Relasi ke seluruh anggota tim (termasuk ketua jika dimasukkan juga)
     */
    public function anggota(): BelongsToMany
    {
        return $this->belongsToMany(
            MahasiswaModel::class,
            't_pendaftaran_mahasiswa',
            'pendaftaran_id',
            'mahasiswa_id'
        )->withTimestamps();
    }
}
