<?php

namespace Tests\Feature;

use App\Models\House;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class HouseTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    //
    public function test_api_super_admin_can_get_all_his_houses()
    {
        $response = $this->actingAs(static::$estateSuperAdmin, 'api')
            ->getJson(route('estate-houses.index'));

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) => $json->has('data')->etc()
            );
    }

    public function test_api_admin_can_get_all_his_houses()
    {
        $response = $this->actingAs(static::$estateAdmin, 'api')
            ->getJson(route('estate-houses.index'));

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) => $json->has('data')->etc()
            );
    }

    public function test_api_super_admin_can_create_a_new_house()
    {
        $houseType = static::$estateSuperAdmin->estate[0]->houseTypes->random();
        $response = $this->actingAs(static::$estateSuperAdmin, 'api')
            ->postJson(route('estate-houses.store'), [
                'name' => 'Test House',
                'address' => 'Test Address',
                'description' => 'Test Description',
                'house_type_id' => $houseType->id,
            ]);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) => $json->has('status')->has('message')->etc()
            );

        $this->assertDatabaseHas('houses', [
            'name' => 'Test House',
            'address' => 'Test Address',
            'description' => 'Test Description',
            'house_type_id' => $houseType->id,
        ]);
    }

    public function test_api_admin_can_create_a_new_house()
    {
        $houseType = static::$estateAdmin->estate[0]->houseTypes->random();
        $response = $this->actingAs(static::$estateAdmin, 'api')
            ->postJson(route('estate-houses.store'), [
                'name' => 'Test House',
                'address' => 'Test Address',
                'description' => 'Test Description',
                'house_type_id' => $houseType->id,
            ]);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) => $json->has('status')->has('message')->etc()
            );

        $this->assertDatabaseHas('houses', [
            'name' => 'Test House',
            'address' => 'Test Address',
            'description' => 'Test Description',
            'house_type_id' => $houseType->id,
        ]);
    }

    public function test_api_super_admin_can_update_a_house()
    {

        $house = static::$estateSuperAdmin->estate[0]->houses[0];
        $response = $this->actingAs(static::$estateSuperAdmin, 'api')
            ->putJson(route('estate-houses.update', $house->id), [
                'name' => 'Test House',
                'address' => 'Test Address',
                'description' => 'Test Description',
                'house_type_id' => $house->house_type_id,
            ]);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) => $json->has('status')->has('message')->etc()
            );

        $this->assertDatabaseHas('houses', [
            'name' => 'Test House',
            'address' => 'Test Address',
            'description' => 'Test Description',
            'house_type_id' => $house->house_type_id,
        ]);
    }

    public function test_api_admin_can_update_a_house()
    {
        $house = static::$estateAdmin->estate[0]->houses[0];
        $response = $this->actingAs(static::$estateAdmin, 'api')
            ->putJson(route('estate-houses.update', $house->id), [
                'name' => 'Test House',
                'address' => 'Test Address',
                'description' => 'Test Description',
                'house_type_id' => $house->house_type_id,
            ]);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) => $json->has('status')->has('message')->etc()
            );

        $this->assertDatabaseHas('houses', [
            'id' => $house->id,
            'name' => 'Test House',
            'address' => 'Test Address',
            'description' => 'Test Description',
            'house_type_id' => $house->house_type_id,
        ]);
    }

    public function test_api_super_admin_can_delete_a_house()
    {
        $house = static::$estateSuperAdmin->estate[0]->houses[0];
        $response = $this->actingAs(static::$estateSuperAdmin, 'api')
            ->deleteJson(route('estate-houses.destroy', $house->id));

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) => $json->has('status')->has('message')->etc()
            );

        $this->assertDatabaseMissing('houses', [
            'id' => $house->id,
        ]);
    }
}
