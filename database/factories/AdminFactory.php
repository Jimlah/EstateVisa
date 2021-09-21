<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Admin::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => $this->faker->randomElement(
                [
                    User::ACTIVE,
                    User::DEACTIVATED,
                    User::SUSPENDED
                ]
            ),
        ];
    }
}
