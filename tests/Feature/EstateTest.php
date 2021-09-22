<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Estate;
use App\Models\EstateAdmin;
use App\Models\Profile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EstateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_super_admin_can_get_all_estate()
    {
        User::factory(10)->create()->each(function ($user) {
            $user->profile()->save(Profile::factory()->make());
            $user->estate_admin()->save(EstateAdmin::factory()->make());
        });

        $response = $this->actingAs(static::$superAdmin, 'api')
            ->getJson(route('estates.index'));

        $response->assertStatus(200);
    }

    public function test_api_admin_can_get_all_estate()
    {
        User::factory(10)->create()->each(function ($user) {
            $user->profile()->save(Profile::factory()->make());
            $user->estate_admin()->save(EstateAdmin::factory()->make());
        });

        $response = $this->actingAs(static::$admin, 'api')
            ->getJson(route('estates.index'));

        $response->assertStatus(200);
    }

    public function test_api_super_admin_can_create_an_estate()
    {
        $data = array_merge(
            Estate::factory()->make()->toArray(),
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray(),
        );

        $response = $this->actingAs(static::$admin, 'api')
            ->postJson(route('estates.store'), $data);

        $response->assertStatus(200);
    }

    public function test_api_admin_can_create_an_estate()
    {
        $data = array_merge(
            Estate::factory()->make()->toArray(),
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray(),
        );

        $response = $this->actingAs(static::$admin, 'api')
            ->postJson(route('estates.store'), $data);

        $response->assertStatus(200);
    }
}
