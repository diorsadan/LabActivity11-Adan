<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    // columns allowed in create(), anything else gets blocked (mass assignment)
    protected $fillable = [
        'post_id',
        'user_id',
        'body',
    ];

    // the comment has post_id so it belongsTo the post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // same thing, user_id = who wrote it
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
