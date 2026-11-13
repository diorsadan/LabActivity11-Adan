<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Post;

class CommentController extends Controller
{
    // StoreCommentRequest already validated the body before this runs
    // Post $post = route model binding, {post} in the url becomes the Post (404 if not found)
    public function store(StoreCommentRequest $request, Post $post)
    {
        // creating through $post->comments() fills in post_id by itself
        // ... spreads the validated array (just body) into this array
        // user_id comes from the logged in user, never from the form so nobody can fake it
        $post->comments()->create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        // with('status') = flash message, the layout shows it once
        return redirect()->route('posts.show', $post->id)->with('status', 'Comment posted.');
    }
}
