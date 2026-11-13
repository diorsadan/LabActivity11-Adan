@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow p-6">
        <div class="flex flex-wrap gap-2 mb-2">
            @if ($post->is_pinned)
                <span class="text-xs bg-yellow-300 text-yellow-900 rounded px-2 py-1">Pinned</span>
            @endif
            <span class="text-xs bg-blue-100 text-blue-800 rounded px-2 py-1">{{ $post->category }}</span>
        </div>

        <h1 class="text-2xl font-bold">{{ $post->title }}</h1>

        <p class="text-sm text-gray-500 mt-1">
            By <a href="{{ route('authors.show', $post->author) }}" class="text-blue-700 hover:underline">{{ $post->author->name }}</a>
            · {{ $post->published_at->format('M d, Y') }} · {{ $post->views }} views
        </p>

        <div class="flex flex-wrap gap-2 mt-3">
            @foreach ($post->tags as $tag)
                <a href="{{ route('posts.index', ['tag' => $tag->name]) }}"
                    class="text-xs bg-gray-200 text-gray-700 rounded-full px-2 py-1 hover:bg-gray-300">#{{ $tag->name }}</a>
            @endforeach
        </div>

        {{-- {{ }} escapes html so no xss --}}
        <p class="mt-4 whitespace-pre-line">{{ $post->body }}</p>

        <a href="{{ route('posts.index') }}" class="inline-block mt-6 text-blue-700 hover:underline">← Back to posts</a>
    </div>

    <div class="bg-white rounded shadow p-6 mt-6">
        <h2 class="text-lg font-semibold mb-4">Comments ({{ $post->comments->count() }})</h2>

        @auth
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-6">
                @csrf
                <label for="body" class="block text-sm font-medium mb-1">Add a comment</label>
                <textarea id="body" name="body" rows="3" class="w-full border border-gray-300 rounded px-3 py-2">{{ old('body') }}</textarea>
                @error('body')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <button type="submit" class="mt-2 bg-blue-700 text-white font-semibold px-4 py-2 rounded hover:bg-blue-800">Post comment</button>
            </form>
        @else
            <p class="mb-6 text-sm">
                <a href="{{ route('login') }}" class="text-blue-700 hover:underline">Sign in to comment</a>
            </p>
        @endauth

        @forelse ($post->comments as $comment)
            <div class="border-t border-gray-200 py-3">
                <p class="text-sm font-semibold">
                    {{ $comment->user->name }}
                    <span class="font-normal text-gray-500">· {{ $comment->created_at->format('M d, Y g:i A') }}</span>
                </p>
                <p class="mt-1">{{ $comment->body }}</p>
            </div>
        @empty
            <p class="text-gray-500">No comments yet.</p>
        @endforelse
    </div>
@endsection
