<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];

    // uses the post_tag pivot table
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
