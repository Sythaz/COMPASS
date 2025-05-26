<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminModel extends Model
{
    use HasFactory;

    protected $table = 't_admin';
    protected $primaryKey = 'admin_id';

    protected $fillable = [
        'user_id',
        'nip_admin',
        'nama_admin',
        'img_admin',
        'kelamin',
        'status',
        'alamat',
        'email',
        'no_hp',
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(UsersModel::class, 'user_id', 'user_id');
    }

}
