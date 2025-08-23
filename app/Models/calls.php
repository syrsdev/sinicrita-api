<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $fillable = [
        'chat_id',
        'status'
    ];

    public function chat()
    {
        return $this->belongsTo(chat_session::class, 'chat_id');
    }
}