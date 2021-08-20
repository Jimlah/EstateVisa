<?php

namespace Tests\Feature;

use App\Models\Estate;
use App\Models\House;
use App\Models\House_type;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class HouseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_super_admin_can_get_all_house()
    {
        House::unsetEventDispatcher();
        $this->withoutEvents();
        User::factory()->create();
        $house =House::factory(10)->create();

        $response = $this->actingAs(User::first(), 'api')
            ->getJson(route('houses.index'));

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data')
         );
    }

    public function test_api_estate_owner_and_estate_admin_can_get_only_there_house()
    {
        House::unsetEventDispatcher();
        User::factory()->create();
        House::factory(10)->create();
        $estate = Estate::factory()->create();
        House::factory(10)->create(['estate_id' => $estate->id]);
        $estate = Estate::find($estate->id);

        $response = $this->actingAs($estate->user, 'api')
            ->getJson(route('houses.index'));

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 10, fn ($json) =>
                $json->where('estate', $estate->name)->etc()
            )
         );
   }

   public function test_api_estate_owner_and_estate_admin_can_create_houses()
   {
        User::factory()->create();
        Estate::factory(10)->create();
        $estate = Estate::find($this->faker->numberBetween(1,Estate::count()));
        House_type::factory(5)->create(['estate_id' => $estate->id]);

        $attributes = [
            'code' => $this->faker->word,
            'description' => $this->faker->sentence,
            'house_type' => $estate->houseTypes->random()->id
        ];

        $response = $this->actingAs($estate->user, 'api')
            ->postJson(route('houses.store'), $attributes);

        $response->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('status')->has('message')
            );

        $this->assertDatabaseHas('houses', [
            'code' => $attributes['code'],
            'description' => $attributes['description'],
            'houses_types_id' => $attributes['house_type'],
            'estate_id' => $estate->id
        ]);
   }

   public function test_api_house_owner_can_not_create_house_without_house_types()
   {
        User::factory()->create();
        Estate::factory(10)->create();
        $estate = Estate::find($this->faker->numberBetween(1,Estate::count()));
        House_type::factory(5)->create(['estate_id' => $estate->id]);

        $attributes = [
            'code' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];

        $response = $this->actingAs($estate->user, 'api')
            ->postJson(route('houses.store'), $attributes);

        $response->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('message')->has('errors')
            );
   }

   public function test_api_house_owner_can_not_create_house_without_house_code()
   {
    User::factory()->create();
    Estate::factory(10)->create();
    $estate = Estate::find($this->faker->numberBetween(1,Estate::count()));
    House_type::factory(5)->create(['estate_id' => $estate->id]);

    $attributes = [
        'description' => $this->faker->sentence,
        'house_type' => $estate->houseTypes->random()->id
    ];

    $response = $this->actingAs($estate->user, 'api')
                    ->postJson(route('houses.store'), $attributes);

    $response->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('message')->has('errors')
        );
   }

//    public function test_api_estate_owner_and_estate_admin_can_get_only_house(Type $var = null)
//    {
//        # code...
//    }
}