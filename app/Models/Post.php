<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    // without this create() throws MassAssignmentException
    protected $fillable = [
        'author_id',
        'title',
        'category',
        'body',
        'is_published',
        'is_pinned',
        'published_at',
        'views',
    ];

    // sqlite saves booleans as 0/1, casts turn them into true/false
    // published_at becomes a date so we can use ->format() in the views
    protected $casts = [
        'is_published' => 'boolean',
        'is_pinned' => 'boolean',
        'published_at' => 'datetime',
    ];

    // posts table has author_id so post belongsTo author
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    // 1 post has many comments (comments.post_id)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // many-to-many through the post_tag pivot table
    // lets me use attach(), sync(), whereHas('tags')
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
