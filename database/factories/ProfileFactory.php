<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = FakerFactory::create();
        return [
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastname,
            'gender' => $faker->randomElement(["male", "female"]),
            'phone_number' => $faker->phoneNumber,
        ];
    }
}