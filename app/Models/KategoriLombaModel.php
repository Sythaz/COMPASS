<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KategoriLombaModel extends Model
{
    use HasFactory;

    protected $table = 't_kategori_lomba';
    protected $primaryKey = 'id_kategori_lomba';
    protected $fillable = [
        'lomba_id',
        'kategori_id',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'id_kategori');
    }

    public function lomba(): BelongsTo
    {
        return $this->belongsTo(LombaModel::class, 'lomba_id', 'id_lomba');
    }
}
