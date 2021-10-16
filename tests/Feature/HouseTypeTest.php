<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Estate;
use App\Models\EstateAdmin;
use Database\Seeders\EstateSeeder;
use Database\Seeders\HouseTypeSeeder;
use Database\Seeders\EstateAdminSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HouseTypeTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            EstateSeeder::class,
            EstateAdminSeeder::class,
            HouseTypeSeeder::class
        ]);

        $this->withoutExceptionHandling();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_super_admin_can_get_all_house_types()
    {
        $user = Estate::all()->random()->owner()->user;
        $response = $this->actingAs($user, 'api')
            ->getJson(route('house-types.index'));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('data')
                    ->etc();
            });
    }

    public function test_api_admin_can_get_all_house_type()
    {
        $user = Estate::all()->random()->admins->random()->user;
        $response = $this->actingAs($user, 'api')
            ->getJson(route('house-types.index'));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('data')
                    ->etc();
            });
    }

    public function test_api_super_admin_create_house_type()
    {
        $user = Estate::all()->random()->owner()->user;
        $response = $this->actingAs($user, 'api')
            ->postJson(route('house-types.store'), [
                'name' => 'test'
            ]);

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')
                    ->has('message')
                    ->etc();
            });

        $this->assertDatabaseHas('house_types', [
            'name' => 'test'
        ]);
    }

    public function test_api_admin_create_house_type()
    {
        $user = Estate::all()->random()->admins->random()->user;
        $response = $this->actingAs($user, 'api')
            ->postJson(route('house-types.store'), [
                'name' => 'test'
            ]);

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')
                    ->has('message')
                    ->etc();
            });

        $this->assertDatabaseHas('house_types', [
            'name' => 'test'
        ]);
    }

    public function test_api_super_admin_update_house_type()
    {
        $user = Estate::all()->random()->owner()->user;

        $houseType = $user->estate->random()->houseTypes->random();

        $response = $this->actingAs($user, 'api')
            ->putJson(route('house-types.update', $houseType->id), [
                'name' => 'test'
            ]);

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')
                    ->has('message')
                    ->etc();
            });

        $this->assertDatabaseHas('house_types', [
            'id' => $houseType->id,
            'name' => 'test'
        ]);
    }

    public function test_api_admin_update_house_type()
    {
        $user = Estate::all()->random()->admins->random()->user;

        $houseType = $user->estate->random()->houseTypes->random();

        $response = $this->actingAs($user, 'api')
            ->putJson(route('house-types.update', $houseType->id), [
                'name' => 'test'
            ]);

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')
                    ->has('message')
                    ->etc();
            });

        $this->assertDatabaseHas('house_types', [
            'id' => $houseType->id,
            'name' => 'test'
        ]);
    }

    public function test_api_super_admin_delete_house_type()
    {
        $user = Estate::all()->random()->owner()->user;

        $houseType = $user->estate->random()->houseTypes->random();

        $response = $this->actingAs($user, 'api')
            ->deleteJson(route('house-types.destroy', $houseType->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')
                    ->has('message')
                    ->etc();
            });

        $this->assertDatabaseMissing('house_types', [
            'id' => $houseType->id
        ]);
    }

    public function test_api_admin_delete_house_type()
    {

        $user = Estate::all()->random()->admins->random()->user;
        $houseType = $user->estate->random()->houseTypes->random();

        $response = $this->actingAs($user, 'api')
            ->deleteJson(route('house-types.destroy', $houseType->id));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('status')
                    ->has('message')
                    ->etc();
            });

        $this->assertDatabaseMissing('house_types', [
            'id' => $houseType->id
        ]);
    }
}
