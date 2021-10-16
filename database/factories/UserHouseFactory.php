<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserHouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserHouseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserHouse::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'status' => $this->faker->randomElement([User::ACTIVE, User::SUSPENDED, User::DEACTIVATED]),
            'is_owner' => false,
        ];
    }

    public function owner()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_owner' => true,
            ];
        });
    }
}
