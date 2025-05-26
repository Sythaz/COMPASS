<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelMinatBakatModel extends Model
{
    use HasFactory;

    protected $table = 't_level_minat_bakat'; 
    protected $primaryKey = 'level_minbak_id';  

    protected $fillable = [
        'level_minbak',
    ];
}
