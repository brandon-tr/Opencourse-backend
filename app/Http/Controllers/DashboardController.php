<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Auth;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $userCount = User::count();
        $totalCourses = Course::count();
        $change = User::where('created_at', '>=', now()->subDays(30))->count();
        $tcChange = Course::where('created_at', '>=', now()->subDays(30))->count();
        return response()->json([
            'timeFrame' => [
                'start' => now()->subDays(30)->format('Y-m-d'),
                'end' => now()->format('Y-m-d'),
                'label' => 'Last 30 Days',
            ],
            'users' => [
                'stat' => $userCount,
                'change' => $change,
                'changeType' => $change > 0 ? 'increase' : $change = 0 ? 'no-change' : 'decrease',
            ],
            'totalCourses' => [
                'stat' => $totalCourses,
                'change' => $tcChange,
                'changeType' => $tcChange > 0 ? 'increase' : $tcChange = 0 ? 'no-change' : 'decrease',
            ]
        ], 200);
    }

    public function getUserList(): JsonResponse
    {
        $users = User::select(['avatar', 'id', 'first_name', "last_name", 'email', 'level'])->get();
        return response()->json([
            'users' => $users,
        ], 200);
    }

    public function getUserSelectOptions(): JsonResponse
    {
        $users = User::select(['id', 'first_name', "last_name", 'avatar'])->get();

        // Combine first_name and last_name
        foreach ($users as $user) {
            $user->name = $user->first_name . ' ' . $user->last_name;
            unset($user->first_name, $user->last_name);
        }

        return response()->json([
            'default' => Auth::user()->id,
            'users' => $users,
        ], 200);
    }
}
