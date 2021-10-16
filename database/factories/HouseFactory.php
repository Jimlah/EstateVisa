<?php

namespace Database\Factories;

use App\Models\House;
use App\Models\HouseType;
use App\Models\User;
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
        return [
            'name' => $this->faker->name,
            'address' => $this->faker->address,
            'description' => $this->faker->text,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (House $house) {
            $house->houseTypes()->attach($house->estate->houseTypes->random()->id);
        });
    }
}
