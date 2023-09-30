<?php

namespace Database\Factories;

use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SiteSettingFactory extends Factory
{
    protected $model = SiteSetting::class;

    public function definition()
    {
        return [
            'site_name' => 'OpenCourse',
            'site_description' => 'Developed with love',
            'site_keywords' => 'new,keyword',
            'site_author' => 'brandon',
            'site_email' => $this->faker->unique()->safeEmail(),
            'site_url' => $this->faker->url(),
            'site_logo' => null,
            'site_favicon' => null,
            'site_facebook' => null,
            'site_youtube' => null,
            'is_registration_enabled' => true,
            'is_email_confirmation_required' => true,
            'is_maintenance_mode' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
