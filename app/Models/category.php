<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $fillable = [
        'id',
        'user_id',
        'category',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
