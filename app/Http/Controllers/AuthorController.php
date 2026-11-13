<?php

namespace App\Http\Controllers;

use App\Models\Author;

class AuthorController extends Controller
{
    // route model binding finds the author or gives 404
    public function show(Author $author)
    {
        $posts = $author->posts()->where('is_published', true)->latest('published_at')->get();

        // counts comments on all of this author's posts (hasManyThrough)
        $commentsCount = $author->comments()->count();

        return view('authors.show', compact('author', 'posts', 'commentsCount'));
    }
}
