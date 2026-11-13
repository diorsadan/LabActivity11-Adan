<?php

namespace App\Http\Controllers;

use App\Models\Author;

class AuthorController extends Controller
{
    // route model binding finds the author or gives 404
    public function show(Author $author)
    {
        // posts() with () = query builder so I can add where/latest
        // only published ones, newest first
        $posts = $author->posts()->where('is_published', true)->latest('published_at')->get();

        // counts comments on all of this author's posts (hasManyThrough)
        // count() runs a COUNT query, doesn't load all the comments
        $commentsCount = $author->comments()->count();

        return view('authors.show', compact('author', 'posts', 'commentsCount'));
    }
}
