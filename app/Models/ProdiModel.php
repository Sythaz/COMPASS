<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdiModel extends Model
{
    use HasFactory;

    protected $table = 't_prodi'; 
    protected $primaryKey = 'prodi_id';  

    protected $fillable = [
        'nama_prodi',
    ];
}
