<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersModel extends Model
{
    use HasFactory;

    protected $table = 't_users'; 
    protected $primaryKey = 'user_id';  

    protected $fillable = [
        'username', 'password', 'role',
    ];
}
