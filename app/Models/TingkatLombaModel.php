<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TingkatLombaModel extends Model
{
    use HasFactory;

    protected $table = 't_tingkat_lomba';
    protected $primaryKey = 'tingkat_lomba_id';

    protected $fillable = [
        'nama_tingkat',
        'status_tingkat_lomba',
    ];
}
