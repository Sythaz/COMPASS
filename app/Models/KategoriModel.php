<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriModel extends Model
{
    use HasFactory;

    protected $table = 't_kategori'; 
    protected $primaryKey = 'kategori_id';  

    protected $fillable = [
        'nama_kategori',
    ];
}
