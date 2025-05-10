<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    use Sluggable;
    protected $guarded = ["id"];

    function user()
    {
        return $this->belongsTo(User::class);
    }
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'content',
                'maxLength' => 30
            ]
        ];
    }
}
