<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'required', 'max:255'],
            'last_name' => ['sometimes', 'required', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:254'],
            'email_verified_at' => ['nullable', 'date'],
            'password' => ['sometimes', 'required', 'confirmed', Rules\Password::defaults()],
            'current_password' => ['required_with:password', 'min:8', 'max:255'],
            'password_confirmation' => ['required_with:password', 'min:8', 'max:255'],
            'avatar' => ['sometimes', 'required', 'image', 'max:5000'],
            'remember_token' => ['nullable'],
            'level' => ['sometimes', 'required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
