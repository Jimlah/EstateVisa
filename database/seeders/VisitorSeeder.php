<?php

namespace Database\Seeders;

use App\Models\Estate;
use App\Models\House;
use App\Models\UserHouse;
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
        // Estate::all()->each(function (Estate $estate) {
        //     $user_id = $estate->houses->random()->houseUsers->random()->user_id;
        //     $estate->visitors()->saveMany(Visitor::factory(5)->make([
        //         'user_id' => $user_id,
        //     ]));

        //     // $estate->visitors()->saveMany(Visitor::factory()->count(5)->sentByEstate()->expireNow()->make([
        //     //     'user_id' => $user_id,
        //     // ]));
        // });
        Estate::all()->each(function (Estate $estate) {
            if ($estate->houses()->exists()) {
                $user = $estate->houses->random()->user->first();
                $estate->visitors()->save(Visitor::factory()->make([
                    'user_id' => $user->id,
                ]));
            }
        });
    }
}
