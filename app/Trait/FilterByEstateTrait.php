<?php
namespace App\Trait;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait FilterByEstateTrait
{
    public static function bootFilterByEstateTrait()
    {
        static::addGlobalScope(
            'FilterByEstate',
            function (Builder $builder) {
                if (auth()->user()?->hasRole(User::ESTATE_OWNER) || auth()->user()?->hasRole(User::ESTATE_ADMIN)) {
                    $builder->where(
                            'estate_id',
                            '=',
                            auth()->user()?->estate?->id
                    );
                }
            }
        );
    }
}