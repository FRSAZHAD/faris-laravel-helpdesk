<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category_id',
        'priority_id',
        'staff_id',
        'status',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
