<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'max:100'],
            'description' => ['sometimes', 'required', 'max:255'],
            'image' => ['sometimes', 'required', 'image'],
            'slug' => ['sometimes', 'required', 'max:100', 'unique:courses'],
            'user_id' => ['sometimes', 'required', 'exists:users,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
