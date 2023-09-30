<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $siteSetting = SiteSetting::select('is_registration_enabled')->first();
        if ($siteSetting->is_registration_enabled == 0) {
            return response()->json([
                'errors' => array(
                    'message' => 'Registration is disabled',
                ),
                'status' => 400,
                'redirect' => '/',
            ], 400);
        }
        if (Auth::check()) {
            return response()->json([
                'errors' => array(
                    'message' => 'You are already logged in',
                ),
                'status' => 400,
                'redirect' => '/',
            ], 400);
        }
        $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password_confirmation' => ['required'],
        ]);

        $user = User::create([
            'first_name' => ucfirst($request->first_name),
            'last_name' => ucfirst($request->last_name),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => 'https://ui-avatars.com/api/?name=' .
                $request->first_name . '+' . $request->last_name

        ]);

        event(new Registered($user));

        Auth::login($user);

        return response()->json([
            'success' => 'User created successfully',
            'status' => 200,
            'redirect' => '/',
        ], 200);
    }
}
