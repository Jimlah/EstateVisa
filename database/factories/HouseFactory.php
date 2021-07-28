<?php

namespace Database\Factories;

use App\Models\Estate;
use App\Models\House;
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
        return [
            'estate_id' => Estate::all()->random()->first(),
            'houses_types_id' => $faker->random_int(1, 20),
            'code' => $faker->word(),
            'description' => $faker->sentence(10),
        ];
    }
}
