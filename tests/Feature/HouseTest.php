<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\House;
use App\Models\Estate;
use Database\Seeders\HouseSeeder;
use Database\Seeders\EstateSeeder;
use Database\Seeders\EstateAdminSeeder;
use Database\Seeders\HouseTypeSeeder;
use Illuminate\Testing\Fluent\AssertableJson;

class HouseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            EstateSeeder::class,
            EstateAdminSeeder::class,
            HouseTypeSeeder::class,
            HouseSeeder::class,
        ]);
    }

    public function test_api_estate_super_admin_can_get_all_his_houses()
    {
        $user = Estate::all()->random()->owner->user;

        $response = $this->actingAs($user, 'api')
            ->getJson(route('houses.index'));

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('data')
                    ->etc()
            );
    }


    public function test_api_estate_admin_can_get_all_his_houses()
    {
        $user = Estate::all()->random()->admins->random()->user;

        $response = $this->actingAs($user, 'api')
            ->getJson(route('houses.index'));

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('data')
                    ->etc()
            );
    }

    public function test_api_super_admin_can_create_a_new_house()
    {

        $user = Estate::all()->random()->owner->user;

        $houseType = $user->estate->random()->houseTypes->random();

        $attributes = array_merge(
            House::factory()->make()->toArray(),
            [
                'house_type_id' => $houseType->id,
            ]
        );


        $response = $this->actingAs($user, 'api')
            ->postJson(route('houses.store'), $attributes);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('status')
                    ->has('message')
                    ->etc()
            );
    }


    public function test_api_admin_can_create_a_house()
    {
        $user = Estate::all()->random()->admins->random()->user;
        $houseType = $user->estate->random()->houseTypes->random();

        $attributes = array_merge(
            House::factory()->make()->toArray(),
            [
                'house_type_id' => $houseType->id,
            ]
        );


        $response = $this->actingAs($user, 'api')
            ->postJson(route('houses.store'), $attributes);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('status')
                    ->has('message')
                    ->etc()
            );
    }

    public function test_api_super_admin_can_update_a_house()
    {
        $user = Estate::all()->random()->owner->user;
        $house = $user->estate->random()->houses->random();

        $houseType = $user->estate->random()->houseTypes->random();

        $attributes = array_merge(
            House::factory()->make()->toArray(),
            [
                'house_type_id' => $houseType->id,
            ]
        );

        $response = $this->actingAs($user, 'api')
            ->putJson(route('houses.update', $house->id), $attributes);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('status')
                    ->has('message')
                    ->etc()
            );
    }

    public function test_api_admin_can_update_a_house()
    {
        $user = Estate::all()->random()->admins->random()->user;
        $house = $user->estate->random()->houses->random();

        $houseType = $user->estate->random()->houseTypes->random();

        $attributes = array_merge(
            House::factory()->make()->toArray(),
            [
                'house_type_id' => $houseType->id,
            ]
        );

        $response = $this->actingAs($user, 'api')
            ->putJson(route('houses.update', $house->id), $attributes);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('status')
                    ->has('message')
                    ->etc()
            );
    }

    public function test_api_super_admin_can_delete_a_house()
    {
        $user = Estate::all()->random()->owner->user;
        $house = $user->estate->random()->houses->random();

        $response = $this->actingAs($user, 'api')
            ->deleteJson(route('houses.destroy', $house->id));

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('status')
                    ->has('message')
                    ->etc()
            );
    }


    public function test_api_admin_can_delete_a_house()
    {
        $user = Estate::all()->random()->admins->random()->user;
        $house = $user->estate->random()->houses->random();

        $response = $this->actingAs($user, 'api')
            ->deleteJson(route('houses.destroy', $house->id));

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('status')
                    ->has('message')
                    ->etc()
            );
    }

}
