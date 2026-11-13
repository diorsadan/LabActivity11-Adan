<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    // so Tag::create(['name' => ...]) works in TagSeeder
    protected $fillable = [
        'name',
    ];

    // other side of Post::tags(), also uses the post_tag pivot table
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
