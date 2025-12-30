<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketHistory extends Model
{
    protected $fillable = [
        'id',
        'ticket_id',
        'description',
        'status',
        'attachment',
        'created_by',
    ];
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
