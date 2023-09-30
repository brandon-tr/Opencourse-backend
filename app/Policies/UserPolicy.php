<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    public function view_dashboard(User $user): Response
    {
        return $user->level >= 2 ? Response::allow() : Response::deny('Unauthorized you do not have permission to view the dashboard.');
    }

    public function is_admin(User $user): Response
    {
        return $user->level > 2 ? Response::allow() : Response::deny('Unauthorized account level is to low for this action');
    }
}
