<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiteSettingController;
use App\Models\SiteSetting;

//Site Admin Routes
Route::middleware(['auth', 'can:view_dashboard,App\Models\User'])->prefix('admin')->group(function () {
    Route::get("/site_settings", SiteSettingController::class . '@indexAdmin');
    Route::post("/update_site_settings", SiteSettingController::class . '@update')->can('update', SiteSetting::class);
    Route::get("/view_course_create", CourseController::class . '@show_create')->can('is_admin,App\Models\User');
    Route::post("/add_course", CourseController::class . '@store')->can('is_admin,App\Models\User');
    Route::get("/stats", DashboardController::class . '@index');
    Route::get("/get_user_list", DashboardController::class . '@getUserList');
});
