<?php

namespace Tests\Feature;

use App\Models\Estate;
use App\Models\House_type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class HouseTypeTest extends TestCase
{

    private $bearer;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_super_admin_can_get_all_house_types()
    {

        $this->login();

        House_type::factory(10)->create();

        $response = $this->json(
            'GET',
            '/api/house-types',
            [],
            [
                'Authorization' => $this->bearer,
                'Content-Type' => 'application/json',
            ]
        );

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('data')
            );
    }

    public function test_api_estate_owner_can_get_only_their_house_types()
    {
        $user = User::factory()->create();
        $estate = Estate::factory()->create(['user_id' => $user->id]);
        $house_type = House_type::factory(10)->create(['estate_id' => $estate->id]);
        $this->login($user);

        $response = $this->json(
            'GET',
            '/api/house-types',
            [],
            [
                'Authorization' => $this->bearer,
                'Content-Type' => 'application/json',
            ]
        );

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('data')
            );
    }

    protected function login(User $user = null)
    {
        if (is_null($user)) {
            $user = User::first();
        }
        $token = $user->createToken('Application')->accessToken;
        $this->bearer = "Bearer $token";
    }
}