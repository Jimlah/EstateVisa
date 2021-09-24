<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Estate;
use App\Models\Profile;
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
        User::factory()->count(10)->create()->each(function ($user) {
            $user->profile()->save(Profile::factory()->make());
            $estate = Estate::factory()->create();
            $estate->user()->attach($user->id, ['role' => User::ESTATE_SUPER_ADMIN]);
            $estate->user()->saveMany(User::factory()->count(4)->make(), ['role' => User::ESTATE_ADMIN])
                ->each(function ($user) use ($estate) {
                    $user->profile()->save(Profile::factory()->make());
                });
        });
    }
}
