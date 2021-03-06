<?php

namespace Database\Factories;

use App\Models\Estate;
use App\Models\User;
use App\Models\EstateAdmin;
use Illuminate\Database\Eloquent\Factories\Factory;

class EstateAdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EstateAdmin::class;

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

    /**
     * Indicate that the model is_owner should be estate super admin.
     *
     * @return $this
     */
    public function owner()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_owner' => true,
            ];
        });
    }
}
