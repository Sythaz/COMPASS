<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaPrometheeModel extends Model
{
    use HasFactory;

    protected $table = 't_kriteria_promethee';
    protected $primaryKey = 'kriteria_promethee_id';

    protected $fillable = [
        'kode_kriteria',
        'nama_kriteria',
        'type',
        'bobot',
        'dynamic_group',
        'preference_function',
    ];
}
