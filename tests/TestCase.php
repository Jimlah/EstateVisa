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
    }
}
