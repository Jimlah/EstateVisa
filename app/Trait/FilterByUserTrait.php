<?php
namespace App\Trait;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait FilterByUserTrait
{
    // public static function bootFilterByUserTrait()
    // {
    //     static::addGlobalScope(
    //         'FilterByUser',
    //         function (Builder $builder) {
    //             if (auth()->user()?->hasRole(User::HOUSE_OWNER) ) {
    //                 $builder
    //                     ->where(
    //                         'user_id',
    //                         '=',
    //                         auth()->user()?->id
    //                 );
    //             }

    //             if (auth()->user()?->hasRole(User::ESTATE_ADMIN) ) {
    //                 $builder->join('estate_users', 'estate_users.estate_id', '=', 'estates.id');
    //             }
    //         }
    //     );
    // }
}
