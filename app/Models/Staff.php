<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'name',
        'email',
        'role',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
