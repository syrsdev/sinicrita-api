<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class messages extends Model
{
    protected $guarded = ["id"];

    function user()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }
}