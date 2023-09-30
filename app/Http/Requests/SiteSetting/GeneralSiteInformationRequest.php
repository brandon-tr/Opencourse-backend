<?php

namespace App\Http\Requests\SiteSetting;

use Illuminate\Foundation\Http\FormRequest;

class GeneralSiteInformationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'site_name' => ['sometimes', 'required', 'max:70'],
            'site_description' => ['sometimes', 'nullable', 'max:500'],
            'site_author' => ['sometimes', 'max:255'],
            'site_email' => ['sometimes', 'nullable', 'email', 'max:254'],
            'site_logo' => ['sometimes', 'nullable', 'image', 'max:5120'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
