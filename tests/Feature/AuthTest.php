<?php

namespace Tests\Feature;

use App\Mail\UserCreated;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

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
        $body = [
            'email' => "ggleichner@example.com",
            'password' => "password",
        ];

        $response = $this->json("POST", '/api/login', $body, ['Accept' => 'application/json']);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>$json->has('token'));

    }



    public function test_api_forgot_password()
    {
        $body = [
          'email' => 'abdullahij951@gmail.com'
        ];

        $response = $this->json('POST', '/api/forgot-password', $body, ['Accept' => 'application/json']);
        $response->assertJson(fn (AssertableJson $json) => $json->has('message')->has('status') );
    }


      /**
     * @test api/auth/logout
     *
     * @return void
     * */
    public function test_api_logout()
    {
        Mail::fake();
        $this->login();

        $response = $this->json("GET", '/api/logout', [], ['Accept' => 'application/json', "Authorization" => $this->bearer]);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
        $json->has('message'));
    }

    protected function login()
    {
        $user = User::first();
        $token = $user->createToken('Application')->accessToken;
        $this->bearer = "Bearer $token";
    }
}