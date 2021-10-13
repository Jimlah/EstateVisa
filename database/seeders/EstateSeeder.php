<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Estate;
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
        // User::factory()->count(10)->create()->each(function ($user) {
        //     $user->profile()->save(Profile::factory()->make());
        //     $estate = Estate::factory()->create();
        //     $estate->houseTypes()->saveMany(HouseType::factory()->count(5)->make());
        //     $estate->user()->attach($user->id, ['role' => User::ESTATE_SUPER_ADMIN]);
        //     $estate->houseTypes()->saveMany(HouseType::factory()->count(5)->make())
        //         ->each(function ($houseType) use ($user) {
        //             $house = House::factory()->create([
        //                 'house_type_id' => $houseType->id,
        //             ]);

        //             $house->estate()->attach([
        //                 'house_type_id' => $houseType->id,
        //             ]);
        //         });

        //     $estate->user()->saveMany(User::factory()->count(4)->make(), ['role' => User::ESTATE_ADMIN])
        //         ->each(function ($user) use ($estate) {
        //             $user->profile()->save(Profile::factory()->make());
        //         });
        // });

        Estate::factory()->count(20)->create();
    }
}
