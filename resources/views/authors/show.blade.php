@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow p-6">
        <h1 class="text-2xl font-bold">{{ $author->name }}</h1>
        <p class="text-gray-500">{{ $author->position }}</p>
        <p class="text-sm text-gray-500 mt-2">{{ $posts->count() }} published posts · {{ $commentsCount }} comments received</p>
    </div>

    <div class="bg-white rounded shadow p-6 mt-6">
        <h2 class="text-lg font-semibold mb-4">Posts</h2>

        @forelse ($posts as $post)
            <div class="border-t border-gray-200 py-3">
                <a href="{{ route('posts.show', $post->id) }}" class="font-semibold hover:underline">{{ $post->title }}</a>
                <p class="text-sm text-gray-500">{{ $post->category }} · {{ $post->published_at->format('M d, Y') }}</p>
            </div>
        @empty
            <p class="text-gray-500">No published posts yet.</p>
        @endforelse

        <a href="{{ route('posts.index') }}" class="inline-block mt-6 text-blue-700 hover:underline">← Back to posts</a>
    </div>
@endsection
