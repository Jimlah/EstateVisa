<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\House;
use App\Models\UsersHouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsersHouseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UsersHouse::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'house_id' => House::factory()->create()->id,
        ];
    }
}