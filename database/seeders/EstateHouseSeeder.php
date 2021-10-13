<?php

namespace Database\Seeders;

use App\Models\House;
use App\Models\Estate;
use Illuminate\Database\Seeder;

class EstateHouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estate::all()->each(function (Estate $estate) {
            $estate->houses()
                ->saveMany(
                    House::factory()
                        ->count(random_int(1, 50))
                        ->make([
                            'house_type_id' => $estate->houseTypes->random()->id,
                        ])
                );
        });
    }
}
