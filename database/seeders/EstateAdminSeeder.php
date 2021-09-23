<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Estate;
use App\Models\Profile;
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
        User::factory(10)->create()->each(function ($user) {
            $user->profile()->save(Profile::factory()->make());
            Estate::factory(1)->create()->each(function ($estate) use ($user) {
                $estate->admin()->save(EstateAdmin::factory()->make(['user_id' => $user->id, 'role' => User::ESTATE_SUPER_ADMIN]));
                $estate
                    ->admin()
                    ->saveMany(EstateAdmin::factory(4)
                        ->make(
                            [
                                'user_id' => function () {
                                    return User::factory()->create()->profile()->save(
                                        Profile::factory()->make()
                                    )->id;
                                },
                                'role' => User::ESTATE_ADMIN
                            ]
                        ));
            });
        });
    }
}
