<?php

namespace Database\Seeders;

use App\Models\Estate;
use App\Models\House;
use App\Models\HouseOwner;
use Illuminate\Database\Seeder;

class HouseOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estate::all()->each(function (Estate $estate) {
            $estate->houses->each(function (House $house) use ($estate) {
                $house->houseOwner()->save(HouseOwner::factory()->make(['estate_id' => $estate->id]));
            });
        });
    }
}
