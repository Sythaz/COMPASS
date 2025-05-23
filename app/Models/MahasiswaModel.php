<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 't_mahasiswa';
    protected $primaryKey = 'mahasiswa_id';

    protected $fillable = [
        'user_id',
        'prodi_id',
        'periode_id',
        'level_minbak_id',
        'nim_mahasiswa',
        'nama_mahasiswa',
        'img_mahasiswa',
        'angkatan',
        'kelamin',
        'status',
        'alamat',
        'email',
        'no_hp',
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(UsersModel::class, 'user_id', 'user_id');
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProdiModel::class, 'prodi_id', 'prodi_id');
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeModel::class, 'periode_id', 'periode_id');
    }

    public function level_minat_bakat(): BelongsTo
    {
        return $this->belongsTo(LevelMinatBakatModel::class, 'level_minbak_id', 'level_minbak_id');
    }
}
