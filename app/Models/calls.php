<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class calls extends Model
{
    protected $fillable = [
        'chat_id',
        'start_time',
        'end_time',
        'status'
    ];

    public function chat()
    {
        return $this->belongsTo(chat_session::class, 'chat_id');
    }
}