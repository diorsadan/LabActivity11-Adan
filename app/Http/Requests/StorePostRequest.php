<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // only users linked to an author can post, residents get 403
    public function authorize(): bool
    {
        return auth()->user()?->author !== null;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:120',
            'body' => 'required|string|min:20',
            'category' => 'required|in:Announcement,Health,Peace & Order,Ayuda,Livelihood',
            'tags' => 'required|array|min:1',
            'tags.*' => 'exists:tags,id',
            'is_published' => 'boolean',
        ];
    }
}
