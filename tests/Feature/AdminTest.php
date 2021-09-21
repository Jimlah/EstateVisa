<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_super_admin_can_get_all_admins()
    {
        User::factory()->create();

        $user = User::first();

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make());
                $u->profile()->save(Profile::factory()->make());
            });

        $response = $this->actingAs($user, 'api')
            ->getJson(route('admins.index'));

        $response->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) {
                    $json->has('data');
                }
            );
    }


    public function test_super_admin_can_create_admins()
    {
        User::factory()->create();
        $user = User::first();

        $data = array_merge(
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray()
        );

        $response = $this->actingAs($user, 'api')
            ->postJson(route('admins.store'), $data);

        $response->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) {
                    $json->has('message')->has('status');
                }
            );


        $this->assertDatabaseHas('profiles', [
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'gender' => $data['gender'],
            'phone_number' => $data['phone_number']
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $data['email']
        ]);

        $this->assertDatabaseHas('admins', [
            'user_id' => User::where('email', $data['email'])->first()->id,
            'status' => User::ACTIVE
        ]);
    }


    public function test_api_super_admin_can_get_a_single_admin()
    {
        User::factory()->create();
        $user = User::first();

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make());
                $u->profile()->save(Profile::factory()->make());
            });

        $response = $this->actingAs($user, 'api')
            ->getJson(route('admins.show', 1));

        $response->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) {
                    $json->has('data');
                }
            );
    }
}
