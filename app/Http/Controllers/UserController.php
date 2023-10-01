<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Log;
use Storage;

class UserController extends Controller
{
    public function CheckIfLoggedIn()
    {
        if (Auth::check()) {
            return response()->json([
                'message' => 'User is logged in',
                'level' => Auth::user()->level,
                'name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                'avatar' => Auth::user()->avatar,
            ], 200);
        }

        return response()->json(['message' => 'User is not logged in'], 401);
    }

    public function me()
    {
        $me = Auth::user();

        if (!$me) {
            throw ValidationException::withMessages([
                'user' => ['User not found'],
            ]);
        }

        $sessionInfo = $me->sessions()->select(['id', 'ip_address', 'user_id', 'last_activity', 'user_agent'])->where('user_id', $me->id)->get();

        if (!$sessionInfo) {
            throw ValidationException::withMessages([
                'session' => ['Session not found'],
            ]);
        }

        return response()->json([
            'first_name' => $me->first_name,
            'last_name' => $me->last_name,
            'email' => $me->email,
            'avatar' => $me->avatar,
            'user_sessions' => $sessionInfo,
        ], 200);
    }

    public function update(UserUpdateRequest $request)
    {
        $user = Auth::user();
        if (!$user) {
            throw ValidationException::withMessages([
                'user' => ['User not found'],
            ]);
        }

        $data = $request->validated();
        $temp = $data['password'] ?? null;

        if (isset($data['first_name'])) {
            $data['first_name'] = ucfirst($data['first_name']);
        }
        if (isset($data['last_name'])) {
            $data['last_name'] = ucfirst($data['last_name']);
        }
        if (isset($data['password'])) {
            if (isset($data['current_password'])) {
                if (!Hash::check($data['current_password'], $user->password)) {
                    throw ValidationException::withMessages([
                        'current_password' => ['The provided password does not match your current password.'],
                    ]);
                }
                if (Hash::check($data['password'], $user->password)) {
                    throw ValidationException::withMessages([
                        'password' => ['The provided password is the same as your current password.'],
                    ]);
                }
            } else {
                return response()->json(['error' => 'Current password is required'], 401);
            }
            $data['password'] = Hash::make($data['password']);
        }

        if ($request->hasFile('avatar')) {
            $fileStored = Storage::put('/avatars', $request->file('avatar'));
            if (!$fileStored) {
                throw ValidationException::withMessages([
                    'avatar' => 'Unable to upload avatar',
                ]);
            }
            $oldFile = str_replace(env("APP_URL") . "/storage/avatars/", "", $user->avatar);
            if (Storage::exists('/avatars/' . $oldFile)) {
                Storage::delete('/avatars/' . $oldFile);
            } else {
                Log::error("Not exists", $oldFile);
            }
            $data['avatar'] = Storage::url($fileStored);
        }

        if (isset($data['password']) && !Auth::logoutOtherDevices($temp)) {
            throw ValidationException::withMessages([
                'password' => ['Something went wrong. Please try again.'],
            ]);
        }

        return response()->json(['success' => 'User updated successfully', 'data' => $data], 200);
    }

    public function logOutOtherSessions(Request $request)
    {
        if (!$request->password) {
            throw ValidationException::withMessages([
                'password' => ['Password is required'],
            ]);
        }

        if (!Hash::check($request->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password does not match your current password.'],
            ]);
        }

        if (Auth::logoutOtherDevices($request->password) && Auth::user()->sessions()->where('id', '!=', Auth::getSession()->getId())->count() > 0) {
            if (Auth::user()->sessions()->where('id', '!=', Auth::getSession()->getId())->delete()) {
                return response()->json(['success' => 'Logged out of other sessions successfully'], 200);
            }
            throw ValidationException::withMessages([
                'unknown' => ['An unknown error occurred. Please try again.'],
            ]);
        }

        throw ValidationException::withMessages([
            'count' => ['There are currently no other active sessions to remove'],
        ]);
    }
}
