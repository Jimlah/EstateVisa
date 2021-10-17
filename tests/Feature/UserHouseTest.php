<?php

namespace Tests\Feature;

use App\Models\House;
use Database\Seeders\EstateAdminSeeder;
use Database\Seeders\EstateSeeder;
use Database\Seeders\HouseSeeder;
use Database\Seeders\HouseTypeSeeder;
use Database\Seeders\UserHouseSeeder;
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
    public function test_house_owner_can_get_all_his_houses()
    {
        $user = House::all()->random()->owner()->user;

        $response = $this->actingAs($user, 'api')
            ->getJson(route('user-houses.index'));

        $response->assertStatus(200)
            ->assertJson(fn ($json) => $json->has('data')->etc());
    }


    public function test_house_owner_can_get_his_house()
    {
        $user = House::all()->random()->owner()->user;
        $house = $user->userHouses->random()->house;

        $response = $this->actingAs($user, 'api')
            ->getJson(route('user-houses.show', $house->id));

        $response->assertStatus(200)
            ->assertJson(fn ($json) => $json->has('data')->etc());
    }
}
