<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Estate;
use App\Models\EstateAdmin;
use App\Models\House;
use App\Models\Profile;
use App\Models\HouseType;
use Illuminate\Database\Seeder;

class EstateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estate::factory()->count(20)->create()->each(function ($estate) {
            $estate->admins()->save(EstateAdmin::factory()->create());
        });
    }
}
