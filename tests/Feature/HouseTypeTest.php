<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class HouseTypeTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_super_admin_can_get_all_house_types()
    {
        $response = $this->actingAs(static::$estateSuperAdmin, 'api')
            ->getJson(route('house-types.index'));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('data')
                    ->etc();
            });
    }

    public function test_api_admin_can_get_all_house_type()
    {
        $response = $this->actingAs(static::$estateAdmin, 'api')
            ->getJson(route('house-types.index'));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('data')
                    ->etc();
            });
    }

    public function test_api_super_admin_create_house_type()
    {
        $response = $this->actingAs(static::$estateSuperAdmin, 'api')
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
        $response = $this->actingAs(static::$estateAdmin, 'api')
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
        $houseType = static::$estateSuperAdmin->estate[0]->houseTypes()->first();
        $response = $this->actingAs(static::$estateSuperAdmin, 'api')
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
        $houseType = static::$estateAdmin->estate[0]->houseTypes()->first();
        $response = $this->actingAs(static::$estateAdmin, 'api')
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
        $houseType = static::$estateSuperAdmin->estate[0]->houseTypes()->first();
        $response = $this->actingAs(static::$estateSuperAdmin, 'api')
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
        $houseType = static::$estateAdmin->estate[0]->houseTypes()->first();
        $response = $this->actingAs(static::$estateAdmin, 'api')
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
