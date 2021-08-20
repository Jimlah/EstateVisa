<?php

namespace App\Policies;

use App\Models\House;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HousePolicy
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
        if ($user->hasRole(User::ESTATE_ADMIN) || $user->hasRole(User::ESTATE_OWNER)) {
        return true;
        };

        if ($user->hasRole(User::SUPER_ADMIN) || $user->hasRole(User::ADMIN)) {
        return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\House  $house
     * @return mixed
     */
    public function view(User $user, House $house)
    {
        if ($user->hasRole(User::ESTATE_ADMIN) || $user->hasRole(User::ESTATE_OWNER)) {
        if ($user->estate?->id == $house->estate_id) {
        return true;
        }
        };

        if ($user->hasRole(User::SUPER_ADMIN) || $user->hasRole(User::ADMIN)) {
        return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->hasRole(User::ESTATE_ADMIN) || $user->hasRole(User::ESTATE_OWNER)) {
        return true;
        };


        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\House  $house
     * @return mixed
     */
    public function update(User $user, House $house)
    {
        if ($user->hasRole(User::ESTATE_ADMIN) || $user->hasRole(User::ESTATE_OWNER)) {
        if ($user->estate?->id == $house->estate_id) {
        return true;
        }
        };


        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\House  $house
     * @return mixed
     */
    public function delete(User $user, House $house)
    {
        if ($user->hasRole(User::ESTATE_ADMIN) || $user->hasRole(User::ESTATE_OWNER)) {
        if ($user->estate?->id == $house->estate_id) {
        return true;
        }
        };


        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\House  $house
     * @return mixed
     */
    public function restore(User $user, House $house)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\House  $house
     * @return mixed
     */
    public function forceDelete(User $user, House $house)
    {
        //
    }
}