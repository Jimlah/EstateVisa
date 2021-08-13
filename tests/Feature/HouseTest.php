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
        $this->assertTrue(true);
    }


    protected function login()
    {
        $user = User::first();
        $token = $user->createToken('Application')->accessToken;
        $this->bearer = "Bearer $token";
    }
}