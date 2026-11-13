<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    // any logged in user can comment
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'body' => 'required|string|min:3|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'body.min' => 'Purok Adan comments need at least 3 characters.',
        ];
    }
}
