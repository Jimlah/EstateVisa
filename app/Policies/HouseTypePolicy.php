<?php

namespace App\Policies;

use App\Models\Estate;
use App\Models\HouseType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HouseTypePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->hasRole(User::ESTATE_SUPER_ADMIN) || $user->hasRole(User::ESTATE_ADMIN)) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HouseType  $houseType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, HouseType $houseType)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HouseType  $houseType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, HouseType $houseType)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HouseType  $houseType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, HouseType $houseType)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HouseType  $houseType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, HouseType $houseType)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HouseType  $houseType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, HouseType $houseType)
    {
        //
    }
}
