<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class chat_session extends Model
{
    protected $guarded = ["id"];

    function user1()
    {
        return $this->belongsTo(User::class, 'user1_id', 'id');
    }
    function user2()
    {
        return $this->belongsTo(User::class, 'user2_id', 'id');
    }
}
