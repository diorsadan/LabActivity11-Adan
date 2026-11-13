<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory;

    // columns allowed in Author::create()
    protected $fillable = [
        'name',
        'position',
        'email',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // all comments on this author's posts, goes through the posts table
    public function comments()
    {
        return $this->hasManyThrough(Comment::class, Post::class);
    }
}
