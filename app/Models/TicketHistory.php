<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketHistory extends Model
{
    protected $fillable = [
        'id',
        'ticket_id',
        'description',
        'staff_id',
        'category_id',
        'status',
        'attachment',
        'created_by',
    ];
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
