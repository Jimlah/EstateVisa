<?php

namespace Database\Factories;

use App\Models\Estate;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class EstateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Estate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $faker = FakerFactory::create();

        return [
            'name' => $faker->name,
            'code' => $faker->unique()->word(),
            'address' => $faker->address,
            'logo' => $faker->imageUrl,
        ];
    }
}