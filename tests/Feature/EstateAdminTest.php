<?php

namespace Tests\Feature;

use App\Models\EstateAdmin;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EstateAdminTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_estate_super_admin_can_view_all_admin()
    {
        $this->withExceptionHandling();
        $response = $this->actingAs(static::$estateSuperAdmin, 'api')
            ->getJson(route('estate-admins.index'));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('data')
                    ->etc();
            });
    }

    public function test_api_estate_super_admin_can_create_new_admin_for_is_estate()
    {
        $attributes = array_merge(
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray()
        );

        $response = $this->actingAs(static::$estateSuperAdmin, 'api')
            ->postJson(route('estate-admins.store'), $attributes);

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('message')
                    ->has('status')
                    ->etc();
            });
    }


    public function test_api_estate_super_admin_can_get_a_single_admin_for_his_estate()
    {
        $this->withoutExceptionHandling();
        $estateAdmin = static::$estateSuperAdmin->estate->random()->estate_admin->random();

        $response = $this->actingAs(static::$estateSuperAdmin, 'api')
            ->getJson(route('estate-admins.show', $estateAdmin->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('data')
                    ->etc();
            });
    }

    public function test_api_estate_super_admin_can_update_a_single_admin_for_his_estate()
    {
        $this->withoutExceptionHandling();
        $estateAdmin = static::$estateSuperAdmin->estate->random()->estate_admin->random();

        $attributes = array_merge(
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray()
        );

        $response = $this->actingAs(static::$estateSuperAdmin, 'api')
            ->putJson(route('estate-admins.update', $estateAdmin->id), $attributes);

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('message')
                    ->has('status')
                    ->etc();
            });

        $this->assertDatabaseHas('users', [
            'email' => $attributes['email']
        ]);

        $this->assertDatabaseHas('profiles', [
            'firstname' => $attributes['firstname'],
            'lastname' => $attributes['lastname'],
            'phone_number' => $attributes['phone_number']
        ]);
    }

    public function test_api_estate_super_admin_can_delete_a_single_admin_for_his_estate()
    {
        $this->withoutExceptionHandling();
        $estateAdmin = static::$estateSuperAdmin->estate->random()->estate_admin->random();

        $response = $this->actingAs(static::$estateSuperAdmin, 'api')
            ->deleteJson(route('estate-admins.destroy', $estateAdmin['id']));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('message')
                    ->has('status')
                    ->etc();
            });

        $this->assertDatabaseMissing('estate_admins', [
            'id' => $estateAdmin['id']
        ]);
    }

    public function test_api_estate_super_admin_can_deactivate()
    {
        $this->withoutExceptionHandling();
        $estateAdmin = static::$estateSuperAdmin->estate->random()->estate_admin->random();

        $response = $this->actingAs(static::$estateSuperAdmin, 'api')
            ->patchJson(route('estate-admins.deactivate', $estateAdmin['id']));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('message')
                    ->has('status')
                    ->etc();
            });

        $this->assertDatabaseHas('estate_admins', [
            'id' => $estateAdmin['id'],
            'status' => User::DEACTIVATED
        ]);
    }

    public function test_api_estate_super_admin_can_activate()
    {
        $this->withoutExceptionHandling();
        $estateAdmin = static::$estateSuperAdmin->estate->random()->estate_admin->random();

        $response = $this->actingAs(static::$estateSuperAdmin, 'api')
            ->patchJson(route('estate-admins.activate', $estateAdmin['id']));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('message')
                    ->has('status')
                    ->etc();
            });

        $this->assertDatabaseHas('estate_admins', [
            'id' => $estateAdmin['id'],
            'status' => User::ACTIVE
        ]);
    }

    public function test_api_estate_super_admin_can_suspend()
    {
        $this->withoutExceptionHandling();
        $estateAdmin = static::$estateSuperAdmin->estate->random()->estate_admin->random();

        $response = $this->actingAs(static::$estateSuperAdmin, 'api')
            ->patchJson(route('estate-admins.suspend', $estateAdmin['id']));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('message')
                    ->has('status')
                    ->etc();
            });

        $this->assertDatabaseHas('estate_admins', [
            'id' => $estateAdmin['id'],
            'status' => User::SUSPENDED
        ]);
    }
}
