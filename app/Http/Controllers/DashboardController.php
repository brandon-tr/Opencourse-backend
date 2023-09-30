<?php

namespace App\Http\Controllers;

use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $change = User::where('created_at', '>=', now()->subDays(30))->count();
        return response()->json([
            'timeFrame' => 'Last 30 days',
            'values' => [
                ['stat' => $userCount,
                    'change' => $change,]
            ]
        ], 200);
    }

    public function getUserList()
    {
        $users = User::select(['avatar', 'id', 'first_name', "last_name", 'email', 'level'])->get();
        return response()->json([
            'users' => $users,
        ], 200);
    }
}
