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
        // with() loads authors and tags in 2 extra queries instead of 1 per post (no N+1)
        // withCount adds $post->comments_count without loading every comment
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
        // whereHas = filter posts by something in a related table (tags)
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
        // pluck = just a list of names, not full models
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

        // load() = eager loading after I already have the post
        // newest comments first, and the user of each comment
        // comments.user = nested, so each comment's name doesn't need its own query
        $post->load(['author', 'tags', 'comments' => fn ($q) => $q->latest(), 'comments.user']);

        return view('posts.show', compact('post'));
    }

    public function create()
    {
        // residents without an author row can't open the form
        // abort_unless = stop with 403 if this is false/null
        abort_unless(auth()->user()->author, 403);

        // full models here (not pluck) because the checkboxes need id + name
        $tags = Tag::orderBy('name')->get();
        $categories = ['Announcement', 'Health', 'Peace & Order', 'Ayuda', 'Livelihood'];

        return view('posts.create', compact('tags', 'categories'));
    }

    // StorePostRequest runs first: authorize() then rules()
    // if anything fails it never reaches this code
    public function store(StorePostRequest $request)
    {
        // tags go to the pivot table, not the posts table
        // safe() = only validated fields, so extra stuff in the form is ignored
        $data = $request->safe()->except('tags');

        // unchecked checkbox is not sent at all, boolean() makes it false
        $data['is_published'] = $request->boolean('is_published');
        // drafts have no published_at (same as Lab 10)
        $data['published_at'] = $data['is_published'] ? now() : null;

        // author_id is filled in by the relationship
        $post = auth()->user()->author->posts()->create($data);
        // sync saves the checked tag ids into post_tag
        $post->tags()->sync($request->validated('tags'));

        return redirect()->route('posts.index')->with('status', 'Post created.');
    }
}
