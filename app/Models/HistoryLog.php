<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryLog extends Model
{
    protected $table = 'historylogs';
    protected $fillable = [
        'user_id',
        'action',
        'module',
        'ip_address',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
