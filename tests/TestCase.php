<?php

namespace Tests;

use App\Models\User;
use App\Models\Admin;
use App\Models\Estate;
use App\Models\House;
use App\Models\HouseOwner;
use App\Models\Profile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker, RefreshDatabase;

    protected static $superAdmin = null;
    protected static $admin = null;
    protected static $estateSuperAdmin = null;
    protected static $estateAdmin = null;
    protected static $houseOwner = null;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('passport:install');
        $this->seed();
        $this->setSuperAdmin();
        $this->setAdmin();
        $this->setEstateSuperAdmin();
        $this->setEstateAdmin();
        $this->setHouseOwner();
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
        return Estate::all()->random()->admins()->first()->user;
    }

    public function create_estate_admin()
    {
        return Estate::all()->random()->admins()->latest()->first()->user;
    }

    public function create_house_owner()
    {
        return House::all()->random()->user;
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

    private function setHouseOwner()
    {
        if (is_null(self::$houseOwner)) {
            self::$houseOwner = $this->create_house_owner();
        }

        return self::$houseOwner;
    }
}
