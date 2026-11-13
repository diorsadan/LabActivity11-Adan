<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
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

    public function create()
    {
        // residents without an author row can't open the form
        abort_unless(auth()->user()->author, 403);

        $tags = Tag::orderBy('name')->get();
        $categories = ['Announcement', 'Health', 'Peace & Order', 'Ayuda', 'Livelihood'];

        return view('posts.create', compact('tags', 'categories'));
    }

    public function store(StorePostRequest $request)
    {
        // tags go to the pivot table, not the posts table
        $data = $request->safe()->except('tags');

        // unchecked checkbox is not sent at all, boolean() makes it false
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? now() : null;

        // author_id is filled in by the relationship
        $post = auth()->user()->author->posts()->create($data);
        $post->tags()->sync($request->validated('tags'));

        return redirect()->route('posts.index')->with('status', 'Post created.');
    }
}
