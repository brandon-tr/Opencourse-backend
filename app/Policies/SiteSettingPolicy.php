<?php

namespace App\Policies;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Lang;

class SiteSettingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function adminSiteSettings(User $user): Response
    {
        return $user->level >= 3 ? Response::allow() : Response::deny(Lang::get("auth.level_to_low"));
    }


    public function update(User $user): Response
    {
        return $user->level >= 3 ? Response::allow() : Response::deny(Lang::get("siteSettings.update_fail_low_level"));
    }

    public function delete(User $user, SiteSetting $siteSetting): bool
    {
        return false;
    }

    public function restore(User $user, SiteSetting $siteSetting): bool
    {
        return $user->level >= 3;
    }

    public function forceDelete(User $user, SiteSetting $siteSetting): bool
    {
        return false;
    }
}
