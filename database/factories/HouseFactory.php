<?php

namespace Database\Factories;

use App\Models\Estate;
use App\Models\House;
use App\Models\House_type;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class HouseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = House::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = FakerFactory::create();
        $estate_id = Estate::factory()->create()->id;
        return [
            'estate_id' => $estate_id,
            'houses_types_id' => House_type::factory(5)->create(['estate_id' => $estate_id])->random(1)->first()->id,
            'code' => $faker->word(),
            'description' => $faker->sentence(10),
        ];
    }
}