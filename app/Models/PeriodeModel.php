<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeModel extends Model
{
    use HasFactory;

    protected $table = 't_periode'; 
    protected $primaryKey = 'periode_id';  

    protected $fillable = [
        'semester_periode',
        'tanggal_mulai',
        'tanggal_akhir',
    ];
}
