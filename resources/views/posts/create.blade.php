@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">New post</h1>

        {{-- error summary at the top, lists every message from StorePostRequest --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded">
                <p class="font-semibold">Please fix the following:</p>
                <ul class="list-disc list-inside text-sm mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('posts.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium mb-1">Title</label>
                {{-- old() on every field so nothing gets erased when there's an error --}}
                <input type="text" id="title" name="title" value="{{ old('title') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category" class="block text-sm font-medium mb-1">Category</label>
                <select id="category" name="category" class="w-full border border-gray-300 rounded px-3 py-2 bg-white">
                    <option value="">Choose a category</option>
                    @foreach ($categories as $category)
                        {{-- @selected keeps the category I picked before the error --}}
                        <option value="{{ $category }}" @selected(old('category') == $category)>{{ $category }}</option>
                    @endforeach
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="body" class="block text-sm font-medium mb-1">Body</label>
                <textarea id="body" name="body" rows="6" class="w-full border border-gray-300 rounded px-3 py-2">{{ old('body') }}</textarea>
                @error('body')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <p class="block text-sm font-medium mb-1">Tags</p>
                <div class="flex flex-wrap gap-4">
                    {{-- name="tags[]" sends an array of tag ids --}}
                    {{-- old('tags', []) = the ids I checked before, [] if it's the first time --}}
                    @foreach ($tags as $tag)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                @checked(in_array($tag->id, old('tags', [])))>
                            {{ $tag->name }}
                        </label>
                    @endforeach
                </div>
                @error('tags')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                {{-- tags.* = error for one of the ids, like if someone sends a fake id --}}
                @error('tags.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                {{-- unchecked = not sent at all, store() uses boolean() to make it false --}}
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published'))>
                    Publish now
                </label>
                @error('is_published')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-blue-700 text-white font-semibold px-4 py-2 rounded hover:bg-blue-800">Save post</button>
        </form>
    </div>
@endsection
