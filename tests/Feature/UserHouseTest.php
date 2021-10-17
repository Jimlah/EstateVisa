<?php

namespace Tests\Feature;

use Database\Seeders\EstateAdminSeeder;
use Database\Seeders\EstateSeeder;
use Database\Seeders\HouseSeeder;
use Database\Seeders\HouseTypeSeeder;
use Database\Seeders\UserHouseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserHouseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            EstateSeeder::class,
            EstateAdminSeeder::class,
            HouseTypeSeeder::class,
            HouseSeeder::class,
            UserHouseSeeder::class,
        ]);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
