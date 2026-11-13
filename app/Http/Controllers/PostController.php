<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // only published posts, drafts are hidden
        // with() loads authors and tags in 2 extra queries instead of 1 per post
        $query = Post::where('is_published', true)
            ->with(['author', 'tags'])
            ->withCount('comments');

        // filled() means it's in the url and not empty
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // only posts that have this tag
        if ($request->filled('tag')) {
            $query->whereHas('tags', fn ($q) => $q->where('name', $request->tag));
        }

        // pinned first, then newest
        // 6 per page because Adan is only 4 letters (min 6)
        // withQueryString keeps ?q= ?category= ?tag= on page 2, 3...
        $posts = $query->orderByDesc('is_pinned')
            ->latest('published_at')
            ->paginate(6)
            ->withQueryString();

        // categories and tags for the dropdowns
        $categories = Post::query()->distinct()->orderBy('category')->pluck('category');
        $tags = Tag::orderBy('name')->pluck('name');

        return view('posts.index', compact('posts', 'categories', 'tags'));
    }

    public function show($id)
    {
        // drafts and missing posts give 404
        $post = Post::where('is_published', true)->findOrFail($id);

        // add 1 view then save to the database
        $post->views++;
        $post->save();

        // newest comments first, and the user of each comment
        $post->load(['author', 'tags', 'comments' => fn ($q) => $q->latest(), 'comments.user']);

        return view('posts.show', compact('post'));
    }
}
