<?php

namespace Tests;

use App\Models\User;
use App\Models\Admin;
use App\Models\Estate;
use App\Models\Profile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase, WithFaker;

    protected static $superAdmin = null;
    protected static $admin = null;
    protected static $estateSuperAdmin = null;
    protected static $estateAdmin = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withExceptionHandling();
        Artisan::call('passport:install');
        $this->seed();
        $this->setSuperAdmin();
        $this->setAdmin();
        $this->setEstateSuperAdmin();
        $this->setEstateAdmin();
    }

    protected function create_super_admin()
    {
        return Admin::first()->user;
    }

    protected function create_admin()
    {
        $user = Admin::find($this->faker()->numberBetween(2, Admin::count()))->user;

        if ($user->hasRole(User::ADMIN)) {
            return $user;
        }
        $this->create_admin();
    }

    public function create_estate_super_admin()
    {
        $estateAdmin = Estate::all()->random()->estateSuperAdmin->first()->user;
        return $estateAdmin;
    }

    public function create_estate_admin()
    {
        return Estate::all()->random()->estate_admin()->latest()->first()->user;
    }

    private function setSuperAdmin()
    {
        if (is_null(self::$superAdmin)) {
            self::$superAdmin = $this->create_super_admin();
        }

        return self::$superAdmin;
    }

    private function setAdmin()
    {
        if (is_null(self::$admin)) {
            self::$admin = $this->create_admin();
        }

        return self::$admin;
    }

    private function setEstateSuperAdmin()
    {
        if (is_null(self::$estateSuperAdmin)) {
            self::$estateSuperAdmin = $this->create_estate_super_admin();
        }

        return self::$estateSuperAdmin;
    }

    private function setEstateAdmin()
    {
        if (is_null(self::$estateAdmin)) {
            self::$estateAdmin = $this->create_estate_admin();
        }

        return self::$estateAdmin;
    }
}
