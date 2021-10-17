<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Visitor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Visitor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'sent_by' => User::class,
            'visited_at' =>  $this->faker->dateTimeBetween('-1 years', 'now'),
            'expired_at' => $this->faker->dateTimeBetween('now', '+1 years'),
        ];
    }


    public function expireNow()
    {
        return $this->state(function (array $attributes) {
            return [
                'expired_at' => now(),
            ];
        });
    }

    public function sentByEstate()
    {
        return $this->state(function (array $attributes) {
            return [
                'sent_by' => Estate::class,
            ];
        });
    }
}
