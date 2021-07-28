<?php

namespace Tests\Feature;

use App\Models\Estate;
use App\Models\User;
use Faker\Factory;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class EstateTest extends TestCase
{


    private $bearer;

    public function test_api_get_all_lestate()
    {
        $this->login();

        $response = $this->json('GET', '/api/estates', [], ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']);
        $response->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => $json->has('data'));
    }

    public function test_api_get_single_estate()
    {
        $this->login();

        $response = $this->json('GET', '/api/estates/' . Estate::all()->random()->first()->id, [], ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']);
        $response->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => $json->has('data'));

    }


    public function test_api_store_new_estate()
    {
        $faker = Factory::create();
        $this->login();
        $data = [
            'estate_name' => $faker->word(),
            'estate_code' => $faker->word(),
            'estate_logo' => $faker->word(),
            'email' => $faker->unique()->safeEmail];

        $response = $this->json('POST', '/api/estates', $data, ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']);
        if ($response->status() == 201) {
            $response->assertStatus(201)
        ->assertJson(fn (AssertableJson $json) => $json->has('message')->has('status'));
        }

        if ($response->status() == 422) {
            $response->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) => $json->has('message')->has('error'));
        }

        $this->assertDatabaseHas('users', ['email' => $data['email']]);
        $this->assertDatabaseHas('estates', ['name' => $data['estate_name'], 'code' => $data['estate_code']]);
        // $this->assertFalse();
    }

    public function test_api_store_new_estate_with_existing_email()
    {
        $faker = Factory::create();
        $this->login();
        $data = [
            'estate_name' => $faker->word(),
            'estate_code' => $faker->word(),
            'estate_logo' => $faker->word(),
            'email' =>User::first()->email
        ];

        $response = $this->json('POST', '/api/estates', $data, ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']);
        $response->assertStatus(422)
        ->assertJson(fn (AssertableJson $json) => $json->has('message')->has('errors'));
    }

    public function test_api_update_estate()
    {
        $faker = Factory::create();
        $this->login();
        $data = [
            'estate_name' => $faker->word(),
            'estate_code' => $faker->word(),
            'estate_logo' => $faker->word(),
        ];

        $response = $this->json('PUT', '/api/estates/' . Estate::all()->random()->first()->id, $data, ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']);
        $response->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => $json->has('message')->has('status'));

        $response->dump();
    }

    public function test_api_delete_estate()
    {
        $this->login();
        $response = $this->json('DELETE', '/api/estates/' . Estate::all()->random()->first()->id, [], ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']);
        if($response->status() == 200){
            $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('message')->has('status'));
        }

        if ($response->status() == 422) {
            $response->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) => $json->has('message')->has('errors'));
        }

        $response->dump();
    }

     protected function login()
    {
        $user = User::first();
        $token = $user->createToken('Application')->accessToken;
        $this->bearer = "Bearer $token";

   }


}
