<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrestasiMahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 't_prestasi_mahasiswa';
    protected $fillable = [
        'prestasi_id',
        'mahasiswa_id',
        'peran',
    ];

    public function prestasi(): BelongsTo
    {
        return $this->belongsTo(PrestasiModel::class, 'prestasi_id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id');
    }
}
