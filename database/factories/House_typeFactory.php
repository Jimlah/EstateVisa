<?php

namespace Database\Factories;

use App\Models\Estate;
use App\Models\User;
use App\Models\House_Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class House_typeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = House_Type::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return
            [
                'estate_id' => Estate::factory()->create()->id,
                'name' => $this->faker->word,
                'description' => $this->faker->sentence,
                'code' => $this->faker->word,
            ];
    }
}