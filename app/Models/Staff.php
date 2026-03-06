<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;
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
