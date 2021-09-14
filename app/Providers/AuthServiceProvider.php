<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\House;
use App\Models\Estate;
use App\Models\House_type;
use App\Models\UsersHouse;
use App\Policies\AdminPolicy;
use App\Policies\HousePolicy;
use App\Policies\EstatePolicy;
use Laravel\Passport\Passport;
use App\Policies\HouseTypePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Estate::class => EstatePolicy::class,
        House_type::class => HouseTypePolicy::class,
        House::class => HousePolicy::class,
        UsersHouse::class => HousePolicy::class,
        Admin::class => AdminPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        //
    }
}
