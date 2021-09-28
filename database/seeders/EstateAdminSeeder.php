<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Estate;
use App\Models\Profile;
use App\Models\HouseType;
use App\Models\EstateAdmin;
use Illuminate\Database\Seeder;

class EstateAdminSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fix a bug here
        User::factory()->count(10)->create()->each(function ($user) {
            $user->profile()->save(Profile::factory()->make());
            $estate = Estate::factory()->create();
            $estate->houseTypes()->saveMany(HouseType::factory()->count(4)->make());
            $estate->user()->attach($user->id, ['role' => User::ESTATE_SUPER_ADMIN]);
            $estate->admin()->saveMany(User::factory()->count(4)->make(), ['role' => User::ESTATE_ADMIN])
                ->each(function ($user) use ($estate) {
                    $user->profile()->save(Profile::factory()->make());
                });
        });
    }
}
