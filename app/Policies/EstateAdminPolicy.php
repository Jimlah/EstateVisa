<?php

namespace App\Policies;

use App\Models\EstateAdmin;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EstateAdminPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->hasRole(User::ESTATE_SUPER_ADMIN)) {
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
     * @param  \App\Models\EstateAdmin  $estateAdmin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, EstateAdmin $estateAdmin)
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
     * @param  \App\Models\EstateAdmin  $estateAdmin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, EstateAdmin $estateAdmin)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EstateAdmin  $estateAdmin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, EstateAdmin $estateAdmin)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EstateAdmin  $estateAdmin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, EstateAdmin $estateAdmin)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EstateAdmin  $estateAdmin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, EstateAdmin $estateAdmin)
    {
        //
    }
}
