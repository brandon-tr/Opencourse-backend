<?php

namespace App\Http\Requests\SiteSetting;

use Illuminate\Foundation\Http\FormRequest;

class SiteSettingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'site_name' => ['sometimes', 'required', 'max:70'],
            'site_description' => ['sometimes', 'nullable', 'max:500'],
            'site_keywords' => ['sometimes', 'nullable', 'max:250'],
            'site_author' => ['sometimes', 'nullable', 'max:255'],
            'site_email' => ['sometimes', 'nullable', 'email', 'max:254'],
            'site_url' => ['sometimes', 'required', 'url', 'max:500'],
            'site_logo' => ['sometimes', 'nullable'],
            'site_favicon' => ['sometimes', 'nullable'],
            'site_facebook' => ['sometimes', 'nullable'],
            'site_youtube' => ['sometimes', 'nullable'],
            'is_registration_enabled' => ['sometimes', 'required', 'boolean'],
            'is_email_confirmation_required' => ['sometimes', 'required', 'boolean'],
            'is_maintenance_mode' => ['sometimes', 'required', 'boolean'],
            'is_google_login_enabled' => ['sometimes', 'required', 'boolean'],
            'google_api_key' => ['sometimes', 'nullable'],
            'text_over_logo' => ['sometimes', 'required', 'boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
