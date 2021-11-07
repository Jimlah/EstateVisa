<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Estate;
use App\Models\Profile;
use Database\Seeders\HouseSeeder;
use Database\Seeders\EstateSeeder;
use Database\Seeders\HouseTypeSeeder;
use Database\Seeders\UserHouseSeeder;
use Database\Seeders\EstateAdminSeeder;
use Illuminate\Testing\Fluent\AssertableJson;

class HouseOwnerTest extends TestCase
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
    public function test_estate_super_admin_can_add_user_to_a_house()
    {
        $user = Estate::all()->random()->owner->user;
        $house = $user->estate->random()->houses->random();

        $attributes = array_merge(
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray()
        );


        $response = $this->actingAs($user, 'api')
            ->postJson(route('owner.store', $house->id), $attributes);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('status')
                    ->has('message')
                    ->etc()
            );
    }

    public function test_estate_super_admin_can_remove_user_from_a_house()
    {
        $user = Estate::all()->random()->owner->user;
        $house = $user->estate->random()->houses->random();
        $houseOwner = $house->owner;

        $response = $this->actingAs($user, 'api')
            ->deleteJson(route('owner.destroy', [$house->id, $houseOwner->id]));

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('status')
                    ->has('message')
                    ->etc()
            );
    }

    public function test_estate_super_admin_can_update_user_from_a_house()
    {
        $user = Estate::all()->random()->owner->user;
        $house = $user->estate->random()->houses->random();
        $houseOwner = $house->owner;

        $attributes = array_merge(
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray()
        );

        $response = $this->actingAs($user, 'api')
            ->putJson(route('owner.update', [$house->id, $houseOwner->id]), $attributes);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('status')
                    ->has('message')
                    ->etc()
            );
    }

    public function test_estate_admin_can_view_user_house_info()
    {
        $user = Estate::all()->random()->owner->user;
        $house = $user->estate->random()->houses->random();
        $houseOwner = $house->owner;

        $response = $this->actingAs($user, 'api')
            ->getJson(route('owner.show', [$house->id, $houseOwner->id]));

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('data')
                    ->etc()
            );
    }
}
