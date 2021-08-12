<?php

namespace App\Policies;

use App\Models\Estate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EstatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        // dd('Are you sure you want to do that?');
        return $user->hasRole(User::SUPER_ADMIN);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Estate  $estate
     * @return mixed
     */
    public function view(User $user, Estate $estate)
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
     * @param  \App\Models\Estate  $estate
     * @return mixed
     */
    public function update(User $user, Estate $estate)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Estate  $estate
     * @return mixed
     */
    public function delete(User $user, Estate $estate)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Estate  $estate
     * @return mixed
     */
    public function restore(User $user, Estate $estate)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Estate  $estate
     * @return mixed
     */
    public function forceDelete(User $user, Estate $estate)
    {
        //
    }
}