<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekomPesertaLombaModel extends Model
{
    use HasFactory;

    protected $table = 't_rekom_peserta_lomba'; 
    protected $primaryKey = 'rekom_peserta_id';  

    protected $fillable = [
        'mahasiswa_id', 
        'lomba_id', 
        'dosen_id',
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
}
