<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UsersHouse;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsersHousePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasRole(User::SUPER_ADMIN) || $user->hasRole(User::ADMIN)) {
           return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->hasRole(User::SUPER_ADMIN) || $user->hasRole(User::ADMIN)) {
            return true;
        }

        if ($user->hasRole(User::HOUSE_OWNER)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UsersHouse  $usersHouse
     * @return mixed
     */
    public function view(User $user, UsersHouse $usersHouse)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UsersHouse  $usersHouse
     * @return mixed
     */
    public function update(User $user, UsersHouse $usersHouse)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UsersHouse  $usersHouse
     * @return mixed
     */
    public function delete(User $user, UsersHouse $usersHouse)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UsersHouse  $usersHouse
     * @return mixed
     */
    public function restore(User $user, UsersHouse $usersHouse)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UsersHouse  $usersHouse
     * @return mixed
     */
    public function forceDelete(User $user, UsersHouse $usersHouse)
    {
        //
    }
}