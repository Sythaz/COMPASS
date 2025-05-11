<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendaftaranLombaModel extends Model
{
    use HasFactory;

    protected $table = 't_pendaftaran'; 
    protected $primaryKey = 'pendaftaran_id';  

    protected $fillable = [
        'mahasiswa_id', 
        'lomba_id',
    ];

    public function mahasiswa(): BelongsTo {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id','mahasiswa_id');
    }

    public function lomba(): BelongsTo {
        return $this->belongsTo(LombaModel::class, 'lomba_id','lomba_id');
    }
}
