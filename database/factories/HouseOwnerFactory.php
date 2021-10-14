<?php

namespace Database\Factories;

use App\Models\HouseOwner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HouseOwnerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HouseOwner::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'status' =>  $this->faker->randomElement([User::ACTIVE, USER::SUSPENDED, User::DEACTIVATED])
        ];
    }
}
