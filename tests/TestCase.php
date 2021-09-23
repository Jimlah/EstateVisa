<?php

namespace Tests;

use App\Models\User;
use App\Models\Admin;
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

    protected function setUp(): void
    {
        parent::setUp();
        $this->withExceptionHandling();
        Artisan::call('passport:install');
        $this->setSuperAdmin();
        $this->setAdmin();
    }

    protected function create_super_admin()
    {
        User::factory()->create()->each(function ($u) {
            $u->admin()->save(Admin::factory()->make());
            $u->profile()->save(Profile::factory()->make());
        });

        return Admin::first()->user;
    }

    protected function create_admin()
    {
        User::factory()->count(10)->create()->each(function ($u) {
            $u->admin()->save(Admin::factory()->make());
            $u->profile()->save(Profile::factory()->make());
        });

        return Admin::find($this->faker()->numberBetween(2, Admin::count()))->user;
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
}
