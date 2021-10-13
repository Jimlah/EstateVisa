<?php

namespace Database\Seeders;

use App\Models\Estate;
use App\Models\HouseType;
use Illuminate\Database\Seeder;

class HouseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estate::all()->each(function (Estate $estate) {
            $estate->houseTypes()->saveMany(HouseType::factory()->count(rand(3, 6))->make());
        });
    }
}
