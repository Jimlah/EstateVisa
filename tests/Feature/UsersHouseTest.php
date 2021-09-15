<?php

namespace Tests\Feature;

use App\Http\Resources\UserResource;
use App\Models\Estate;
use App\Models\House;
use App\Models\House_type;
use App\Models\User;
use App\Models\UsersHouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UsersHouseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_estate_admin_and_admin_can_get_all_users_with_their_house()
    {
        House::unsetEventDispatcher();
        House_type::unsetEventDispatcher();
        User::factory()->create();
        UsersHouse::factory(10)->create();

        $user = User::first();
        $response = $this->actingAs($user, 'api')
            ->get(route('users-house.index'));

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('data')
            );
    }

}
