<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Visitor;
use App\Models\UserHouse;
use App\Notifications\GatePassIssued;
use Database\Seeders\HouseSeeder;
use Database\Seeders\EstateSeeder;
use Database\Seeders\VisitorSeeder;
use Database\Seeders\HouseTypeSeeder;
use Database\Seeders\UserHouseSeeder;
use Database\Seeders\EstateAdminSeeder;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

class VisitorTest extends TestCase
{

    protected $seederHasRunOnce = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            EstateSeeder::class,
            EstateAdminSeeder::class,
            HouseTypeSeeder::class,
            HouseSeeder::class,
            UserHouseSeeder::class,
            VisitorSeeder::class,
        ]);
    }

    public function test_api_house_users_can_get_all_visitor()
    {
        $house = UserHouse::all()->random();
        $user = $house->user;


        $response = $this->actingAs($user, 'api')
            ->getJson(route('visitors.index'));

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->has('data')
                    ->etc()
            );
    }

    public function test_api_house_users_can_get_visitor_by_id()
    {
        $house = UserHouse::all()->random();
        $user = $house->user;
        $visitor = $user->visitors->random();

        $response = $this->actingAs($user, 'api')
            ->getJson(route('visitors.show', $visitor->id));

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->has('data')
                    ->etc()
            );
    }

    public function test_api_house_users_can_create_a_visitor_with_valid_data()
    {
        Notification::fake();
        $userHouse = UserHouse::all()->random();
        $user = $userHouse->user;

        $attributes = array_merge(
            Visitor::factory()->make()->toArray(),
            [
                'estate_id' => $user->userHouses->random()->house->estate_id,
            ]
        );

        $response = $this->actingAs($user, 'api')
            ->postJson(route('visitors.store'), $attributes);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->has('status')
                    ->has('message')
                    ->etc()
            );

        Notification::assertSentTo(
            $userHouse->house->estate->admins->each(function ($admin) {
                return $admin->user;
            }),
            GatePassIssued::class
        );
    }

    public function test_api_house_users_can_update_a_visitor_with_valid_data()
    {
        $user = UserHouse::all()->random()->user;

        $visitor = $user->visitors()->save(Visitor::factory()->make([
            "estate_id" => $user->userHouses->random()->house->estate_id,
        ]));

        $attributes = array_merge(
            Visitor::factory()->make()->toArray(),
        );

        $response = $this->actingAs($user, 'api')
            ->putJson(route('visitors.update', $visitor->id), $attributes);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->has('status')
                    ->has('message')
                    ->etc()
            );
    }

    public function test_api_house_users_can_delete_a_visitor()
    {
        $user = UserHouse::all()->random()->user;

        $visitor = $user->visitors()->save(Visitor::factory()->make([
            "estate_id" => $user->userHouses->random()->house->estate_id,
        ]));

        $response = $this->actingAs($user, 'api')
            ->deleteJson(route('visitors.destroy', $visitor->id));

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->has('status')
                    ->has('message')
                    ->etc()
            );
    }
}
