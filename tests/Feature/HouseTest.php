<?php

namespace Tests\Feature;

use App\Models\House;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HouseTest extends TestCase
{

    private $bearer;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_get_all_house()
    {
        House::factory(10)->create();
        $response = $this->json('GET', '/api/houses', [], ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']);

        $response->assertStatus(200);
    }


    protected function login()
    {
        $user = User::first();
        $token = $user->createToken('Application')->accessToken;
        $this->bearer = "Bearer $token";
    }
}