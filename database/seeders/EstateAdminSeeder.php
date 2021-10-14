<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Estate;
use App\Models\Profile;
use App\Models\HouseType;
use App\Models\EstateAdmin;
use App\Models\House;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;

class EstateAdminSeeder extends Seeder
{
    use WithFaker;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estate::all()->each(function (Estate $estate) {
            $estate
                ->estate_admin()
                ->save(EstateAdmin::factory()->superAdmin()->make());
        });

        Estate::all()->each(function (Estate $estate) {
            $estate
                ->estate_admin()
                ->save(EstateAdmin::factory()->make());
        });
    }
}
