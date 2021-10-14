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
        Admin::factory(5)->create();
    }
}
