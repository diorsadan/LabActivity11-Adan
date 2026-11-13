<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // only users linked to an author can post, residents get 403
    // ?-> = if there's no user it gives null instead of crashing
    public function authorize(): bool
    {
        return auth()->user()?->author !== null;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:120',
            'body' => 'required|string|min:20',
            // in: = only my 5 categories, so nobody can send a made-up one
            'category' => 'required|in:Announcement,Health,Peace & Order,Ayuda,Livelihood',
            // tags[] checkboxes come in as an array, need at least 1 checked
            'tags' => 'required|array|min:1',
            // .* = check each id in the array, it must exist in the tags table
            'tags.*' => 'exists:tags,id',
            'is_published' => 'boolean',
        ];
    }
}
