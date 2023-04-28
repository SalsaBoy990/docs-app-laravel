<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can upload to the root of the categories.
     *
     * @param  User  $user
     *
     * @return Response|bool
     */
    public function authorize_upload_to_root(User $user): \Illuminate\Auth\Access\Response|bool
    {
        $permissions = $user->permissions;
        if (!$permissions) {
            return false;
        }
        if (array_key_exists(User::PERMISSIONS['upload_root'], $permissions)) {
            return $permissions[User::PERMISSIONS['upload_root']];
        }
        return false;
    }

}
