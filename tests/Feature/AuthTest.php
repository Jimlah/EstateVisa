<?php

namespace Tests\Feature;

use App\Mail\UserCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\Passport;

class AuthTest extends TestCase
{
    private $bearer;

    /**
     * @test api/auth/login
     *
     * @return void
     *  */
    public function test_api_login()
    {
        User::unsetEventDispatcher();
        $user =  User::factory()->create();

        $body = [
            'email' => $user->email,
            'password' => "password",
        ];

        $response = $this->json("POST", '/api/login', $body, ['Accept' => 'application/json']);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('token')->has('user')->etc());
    }

    public function test_api_will_not_login_with_invalid_data()
    {
        User::unsetEventDispatcher();
        $user =  User::factory()->create();

        $body = [
            'email' => $user->email,
            'password' => "pentacle",
        ];

        $response = $this->json("POST", '/api/login', $body, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json->has('message')->has('status'));
    }


    public function test_api_forgot_password()
    {
        $body = [
            'email' => 'abdullahij951@gmail.com'
        ];

        $response = $this->json('POST', '/api/forgot-password', $body, ['Accept' => 'application/json']);
        $response->assertJson(fn (AssertableJson $json) => $json->has('message')->has('status'));
    }


    /**
     * @test api/auth/logout
     *
     * @return void
     * */
    public function test_api_logout()
    {
        Artisan::call('passport:install');
        $this->withExceptionHandling();
        Mail::fake();
        $this->login();
        $user = User::factory()->create();
        Passport::actingAs($user);
        $token = $user->createToken('Application')->accessToken;

        $response = $this->json("GET", '/api/logout', [], ['Accept' => 'application/json', "Authorization" => 'Bearer ' . $token]);
        dd($response);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('message'));
    }

    protected function login()
    {
        User::factory()->create();
        $user = User::first();
        $token = $user->createToken('Application')->accessToken;
        $this->bearer = "Bearer $token";
    }
}
