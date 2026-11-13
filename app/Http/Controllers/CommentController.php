<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Post;

class CommentController extends Controller
{
    // StoreCommentRequest already validated the body before this runs
    public function store(StoreCommentRequest $request, Post $post)
    {
        $post->comments()->create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('posts.show', $post->id)->with('status', 'Comment posted.');
    }
}
