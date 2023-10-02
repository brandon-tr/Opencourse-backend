<?php

use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::middleware([])->group(function () {
    Route::get('check_logged_in', [UserController::class, 'CheckIfLoggedIn']);
});

Route::middleware([])->group(function () {
    Route::get('site_logo', [SiteSettingController::class, 'returnSiteLogo']);
    Route::get('navbar_info', [SiteSettingController::class, 'navbarInfo']);
});

require __DIR__ . '/auth.php';
require __DIR__ . '/admin/dashboard/dashboard.php';
