<?php

namespace Database\Seeders;

use App\Models\Estate;
use App\Models\Visitor;
use Illuminate\Database\Seeder;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estate::all()->each(function (Estate $estate) {
            $estate->visitors()->saveMany(Visitor::factory()->count(5)->make([
                'user_id' => $estate->houses()->inRandomOrder()->first()->houseUsers()->inRandomOrder()->first()->user_id,
            ]));

            $estate->visitors()->saveMany(Visitor::factory()->count(5)->sentByEstate()->expireNow()->make([
                'user_id' => $estate->houses()->inRandomOrder()->first()->houseUsers()->inRandomOrder()->first()->user_id,
            ]));
        });
    }
}
