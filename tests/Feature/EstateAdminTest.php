<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EstateAdminTest extends TestCase
{

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


    public function test_api_estate_super_admin_can_get_a_single_admin_for_is_estate()
    {
        $estateAdmins = static::$estateSuperAdmin->estate[0]->user->toArray();
        $estateAdmin = $estateAdmins[$this->faker()->randomElement(array_keys($estateAdmins))];
        // dd($estateAdmin['id']);

        $response = $this->actingAs(static::$estateSuperAdmin, 'api')
            ->getJson(route('estate-admins.show', $estateAdmin['id']));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('data')
                    ->etc();
            });
    }
}
