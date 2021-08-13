<?php

namespace Tests\Feature;

use App\Models\Estate;
use App\Models\User;
use Faker\Factory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class EstateTest extends TestCase
{


    private $bearer;

    public function test_api_get_all_estate_for_super_admin()
    {
        Artisan::call('migrate');
        Estate::factory(10)->create();
        $this->login();

        $response = $this->json('GET', '/api/estates', [], ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('data'));
    }

    public function test_api_can_not_get_access_for_non_super_admin()
    {
        Artisan::call('migrate');
        $user = User::find(rand(2, User::count()));
        $bearer = $user->createToken('Application')->accessToken;
        $bearer = 'Bearer ' . $bearer;

        $response = $this->json('GET', '/api/estates', [], ['Authorization' => $bearer, 'Content-Type' => 'application/json']);
        $response->assertStatus(403);
    }


    public function test_api_can_create_a_new_estate()
    {
        $this->login();
        $estate = Estate::factory()->make();
        $faker = Factory::create();
        $estate->email = $faker->email;


        $response = $this->json(
            'POST',
            '/api/estates',
            [
                'email' => $estate->email,
                'estate_name' => $estate->name,
                'estate_code' => $estate->code,
            ],
            ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']
        );

        $response->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) => $json->has('status')->has('message'));
        $this->assertDatabaseHas('estates', [
            'name' => $estate->name,
            'code' => $estate->code,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $estate->email,
        ]);
    }

    public function test_api_can_not_create_a_new_estate_without_email()
    {
        $this->login();
        $estate = Estate::factory()->make();

        $response = $this->json(
            'POST',
            '/api/estates',
            [
                'estate_name' => $estate->name,
                'estate_code' => $estate->code,
            ],
            ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']
        );
        $response->assertStatus(422);

        $this->assertDatabaseMissing('estates', [
            'name' => $estate->name,
            'code' => $estate->code,
        ]);
    }

    public function test_api_can_not_create_a_new_estate_without_estate_name()
    {
        $this->login();
        $estate = Estate::factory()->make();
        $faker = Factory::create();
        $estate->email = $faker->email;

        $response = $this->json(
            'POST',
            '/api/estates',
            [
                'email' => $estate->email,
                'estate_code' => $estate->code,
            ],
            ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']
        );
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'email' => $estate->email,
        ]);
    }

    public function test_api_can_not_create_a_new_estate_without_estate_code()
    {
        $this->login();
        $estate = Estate::factory()->make();
        $faker = Factory::create();
        $estate->email = $faker->email;

        $response = $this->json(
            'POST',
            '/api/estates',
            [
                'email' => $estate->email,
                'estate_name' => $estate->name,
            ],
            ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']
        );

        $response->assertStatus(422);
        $this->assertDatabaseMissing('estates', [
            'name' => $estate->name,
            'code' => $estate->code,
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => $estate->email,
        ]);
    }

    public function test_api_super_admin_get_single_estate()
    {
        $this->login();

        $estate = Estate::factory()->create();
        $response = $this->json('GET', '/api/estates/' . $estate->id, [], ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('data'));
    }

    public function test_api_estate_owner_can_only_get_their_estate()
    {
        $user = User::factory()->create();
        $estate = Estate::factory()->create(['user_id' => $user->id]);
        $this->login($user);

        $response = $this->json('GET', '/api/estates/' . $estate->id, [], ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('data'));
    }

    public function test_api_estate_owner_can_not_get_other_estate()
    {
        $user = User::factory()->create();
        $estate = Estate::factory()->create();
        $this->login($user);

        $response = $this->json('GET', '/api/estates/' . $estate->id, [], ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']);
        $response->assertStatus(403);
    }

    public function test_api_super_admin_can_update_an_estate()
    {
        $this->login();
        $estate = Estate::factory()->create();
        $faker = Factory::create();
        $name = $faker->name;

        $response = $this->json(
            'PUT',
            '/api/estates/' . $estate->id,
            [
                'estate_name' => $name,
                'estate_code' => $estate->code,
            ],
            ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']
        );
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('status')->has('message'));

        $this->assertDatabaseHas('estates', [
            'name' => $name,
            'code' => $estate->code,
        ]);
    }

    public function test_api_estate_owner_can_update_an_estate()
    {
        $user = User::factory()->create();
        $estate = Estate::factory()->create(['user_id' => $user->id]);
        $faker = Factory::create();
        $name = $faker->name;

        $this->login($user);
        $response = $this->json(
            'PUT',
            '/api/estates/' . $estate->id,
            [
                'estate_name' => $name,
                'estate_code' => $estate->code,
            ],
            ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']
        );
        $response->assertStatus(200);
        $this->assertDatabaseHas('estates', [
            'name' => $name,
            'code' => $estate->code,
        ]);
    }

    public function test_api_estate_owner_can_not_update_other_estate()
    {
        $user = User::factory()->create();
        $estate = Estate::factory()->create();
        $faker = Factory::create();
        $name = $faker->name;

        $this->login($user);
        $response = $this->json(
            'PUT',
            '/api/estates/' . $estate->id,
            [
                'estate_name' => $name,
                'estate_code' => $estate->code,
            ],
            ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']
        );
        $response->assertStatus(403);
        $this->assertDatabaseMissing('estates', [
            'name' => $name,
            'code' => $estate->code,
        ]);

        $this->assertDatabaseHas('estates', [
            'name' => $estate->name,
            'code' => $estate->code,
        ]);
    }

    public function test_api_super_admin_can_delete_an_estate()
    {
        $this->login();
        $estate = Estate::factory()->create();
        $response = $this->json('DELETE', '/api/estates/' . $estate->id, [], ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('status')->has('message'));
    }


    public function test_api_non_super_admin_can_not_delete_an_estate()
    {
        $user = User::factory()->create();
        $estate = Estate::factory()->create();
        $this->login($user);
        $response = $this->json('DELETE', '/api/estates/' . $estate->id, [], ['Authorization' => $this->bearer, 'Content-Type' => 'application/json']);
        $response->assertStatus(403);
    }


    protected function login(User $user = null)
    {
        Artisan::call('migrate');
        if (!$user) {
            $user = User::first();
        }
        $token = $user->createToken('Application')->accessToken;
        $this->bearer = "Bearer $token";
    }
}