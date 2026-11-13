<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory;

    // columns allowed in Author::create()
    // user_id added so UserSeeder can do $author->update(['user_id' => ...])
    protected $fillable = [
        'name',
        'position',
        'email',
        'user_id',
    ];

    // belongsTo because the user_id column is in the authors table
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 1 author has many posts (posts.author_id)
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // all comments on this author's posts, goes through the posts table
    // authors -> posts.author_id -> comments.post_id
    public function comments()
    {
        return $this->hasManyThrough(Comment::class, Post::class);
    }
}
