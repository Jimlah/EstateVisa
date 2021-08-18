<?php

namespace Tests\Feature;

use App\Models\Estate;
use App\Models\House;
use App\Models\House_type;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class HouseTypeTest extends TestCase
{


    /**
     * test_api_super_admin_can_get_all_house_types
     *
     * @return void
     */
    public function test_api_super_admin_can_get_all_house_types()
    {

        User::factory()->create();
        $user = User::find(1);

        House_type::factory(10)->create();
        $this->actingAs($user, 'api');

        $response = $this->json(
            'GET',
            route('house-types.index'),
        );

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('data')
            );
    }

    public function test_api_estate_owner_can_get_only_their_house_types()
    {

        User::factory()->create();
        House_type::factory()->create();

        $user = User::find($this->faker->numberBetween(2, User::count()));

        $this->actingAs($user, 'api');
        $response = $this->json(
            'GET',
            route('house-types.index'),
        );

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('data')
            );
    }

    public function test_api_estate_owner_can_create()
    {
        User::factory(5)->create();
        $user = User::find(5);
        $estate = Estate::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user, 'api');
        $attributes = [
            'name' => $this->faker->word,
            'description' => $this->faker->word,
            'code' => $this->faker->word,
        ];
        $response = $this->postJson(
            route('house-types.store'),
            $attributes
        );
        $response->assertStatus(201);
        $response->assertJson(
            fn (AssertableJson $json) =>
                $json->hasAll(['status', 'message'])
        );

        $this->assertDatabaseHas('house_types', $attributes)
        ;
    }

    public function test_api_user_can_not_create_if_not_estate_owner_or_estate_admin()
    {
        User::factory(5)->create();
        $user = User::find(5);
        $estate = Estate::factory()->create();

        $this->actingAs($user, 'api');

        $attributes = [
            'name' => $this->faker->word,
            'description' => $this->faker->word,
            'code' => $this->faker->word,
        ];
        $response = $this->postJson(
            route('house-types.store'),
            $attributes
        );
        $response->assertStatus(403);

        $this->assertDatabaseMissing('house_types', $attributes);

    }

    public function test_api_estate_owner_can_update_only_their_house_types()
    {
        User::factory()->create();
        $house_type = House_type::factory(10)->create();

        $house_type =  House_type::find($this->faker->numberBetween(2, User::count()));
        $estate = Estate::find($house_type->estate_id);


        $this->actingAs($estate->user, 'api');
        $attributes = [
            'name' => $this->faker->word,
            'description' => $this->faker->word,
            'code' => $this->faker->word,
        ];



        $response = $this->putJson(
            route('house-types.update', $house_type->id),
            $attributes
        );
        $response->dump();


    $response->assertStatus(200);

        $this->assertDatabaseHas('house_types', $attributes);

    }


}