<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make());
                $u->profile()->save(Profile::factory()->make());
            });

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make(['status' => User::SUSPENDED]));
                $u->profile()->save(Profile::factory()->make());
            });

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($u) {
                $u->admin()->save(Admin::factory()->make(['status' => User::DEACTIVATED]));
                $u->profile()->save(Profile::factory()->make());
            });
    }
}
