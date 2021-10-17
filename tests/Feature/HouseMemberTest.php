<?php

namespace Tests\Feature;

use Attribute;
use Tests\TestCase;
use App\Models\User;
use App\Models\House;
use App\Models\Profile;
use Database\Seeders\HouseSeeder;
use Database\Seeders\EstateSeeder;
use Database\Seeders\HouseTypeSeeder;
use Database\Seeders\UserHouseSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HouseMemberTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            EstateSeeder::class,
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
    public function test_house_owner_can_get_is_house_members()
    {
        $user = House::all()->random()->owner()->user;
        $house  = $user->userHouses->random()->house;

        $response = $this->actingAs($user, 'api')
            ->getJson(route('member.index', $house->id));

        $response->assertStatus(200);
    }

    public function test_api_house_owner_can_add_a_new_member_to_his_house()
    {
        $user = House::all()->random()->owner()->user;
        $house  = $user->userHouses->random()->house;

        $attributes = array_merge(
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray()
        );

        $response = $this->actingAs($user, 'api')
            ->postJson(route('member.store', $house->id), $attributes);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->has('message')
                    ->has('status')->etc()
            );
    }

    public function test_api_house_owner_can_update_a_member_of_his_house()
    {
        $user = House::all()->random()->owner()->user;
        $house  = $user->userHouses->random()->house;

        $member = $house->members()->inRandomOrder()->first();

        $attributes = array_merge(
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray()
        );

        $response = $this->actingAs($user, 'api')
            ->putJson(route('member.update', [$house->id, $member->id]), $attributes);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->has('message')
                    ->has('status')->etc()
            );
    }

    public function test_api_house_owner_can_delete_a_member_of_his_house()
    {
        $user = House::all()->random()->owner()->user;
        $house  = $user->userHouses->random()->house;

        $member = $house->members()->inRandomOrder()->first();

        $response = $this->actingAs($user, 'api')
            ->deleteJson(route('member.destroy', [$house->id, $member->id]));

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->has('message')
                    ->has('status')->etc()
            );
    }

    public function test_api_house_owner_can_get_a_member_of_his_house()
    {
        $user = House::all()->random()->owner()->user;
        $house  = $user->userHouses->random()->house;

        $member = $house->members()->inRandomOrder()->first();

        $response = $this->actingAs($user, 'api')
            ->getJson(route('member.show', [$house->id, $member->id]));

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->has('data')
                    ->etc()
            );
    }


}
