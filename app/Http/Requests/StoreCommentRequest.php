<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// form request = validation moved out of the controller into its own class
// laravel runs it automatically when the controller type-hints it
class StoreCommentRequest extends FormRequest
{
    // any logged in user can comment
    // false = 403 Forbidden
    public function authorize(): bool
    {
        return auth()->check();
    }

    // if these fail laravel redirects back with $errors and old input
    public function rules(): array
    {
        return [
            'body' => 'required|string|min:3|max:1000',
        ];
    }

    // my own error message, the key is field.rule
    public function messages(): array
    {
        return [
            'body.min' => 'Purok Adan comments need at least 3 characters.',
        ];
    }
}
