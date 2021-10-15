<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class HouseOwnerTest extends TestCase
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
    public function test_house_owner_can_get_all_his_houses()
    {
        $response = $this->actingAs(static::$houseOwner, 'api')
            ->getJson(route('house-owner.index'));

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data')
                ->etc()
        );
    }

    public function test_house_owner_can_view_a_single_house()
    {
        $response = $this->actingAs(static::$houseOwner, 'api')
            ->getJson(route('house-owner.show', static::$houseOwner->houses->first()->id));

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data')
                ->etc()
        );
    }


    public function test_estate_admin_can_add_a_user_to_a_house()
    {
        $attributes = array_merge(
            User::factory()->make()->toArray(),
            Profile::factory()->make()->toArray(),
        );

        $estateAdmin =  static::$estateAdmin;
        $house = $estateAdmin->estate->random()->first()->houses->random()->first();

        $response = $this->actingAs($estateAdmin, 'api')
            ->putJson(route('house.update', $house->id), $attributes);

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('message')
                ->has('status')
                ->etc()
        );
    }

    public function test_estate_admin_can_remove_a_user_from_the_house()
    {
        $estateAdmin =  static::$estateAdmin;
        $house = $estateAdmin->estate->random()->first()->houses->random()->first();

        $response = $this->actingAs($estateAdmin, 'api')
            ->deleteJson(route('house.destroy', $house->id));

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('message')
                ->has('status')
                ->etc()
        );
    }
}
