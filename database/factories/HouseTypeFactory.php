<?php

namespace Database\Factories;

use App\Models\HouseType;
use Illuminate\Database\Eloquent\Factories\Factory;

class HouseTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HouseType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
