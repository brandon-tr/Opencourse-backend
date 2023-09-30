<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name');
            $table->string('site_description')->nullable();
            $table->string('site_keywords')->nullable();
            $table->string('site_author')->nullable();
            $table->string('site_email')->nullable();
            $table->string('site_url');
            $table->string('site_logo')->nullable();
            $table->string('site_favicon')->nullable();
            $table->string('site_facebook')->nullable();
            $table->string('site_youtube')->nullable();
            $table->boolean('is_registration_enabled')->default(true);
            $table->boolean('is_email_confirmation_required')->default(false);
            $table->boolean('is_maintenance_mode')->default(false);
            $table->boolean('is_google_login_enabled')->default(false);
            $table->string('google_api_key')->nullable();
            $table->boolean('text_over_logo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_settings');
    }
};
