<?php

namespace Database\Seeders;

use App\Models\House;
use App\Models\HouseUser;
use App\Models\UserHouse;
use Illuminate\Database\Seeder;

class UserHouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        House::all()->each(function (House $house) {
            $house->houseUsers()->save(UserHouse::factory()->owner()->make());

            $house->houseUsers()->saveMany(UserHouse::factory(4)->make());
        });
    }
}
