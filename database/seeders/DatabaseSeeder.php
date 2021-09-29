<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Estate;
use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'email' => 'prohaska.jayde@example.net'
        ]);

        // Profile::factory()->create();

        // Profile::factory(10)->create();

        Artisan::call('passport:install');

        $this->call([
            AdminSeeder::class,
            EstateAdminSeeder::class,
            EstateSeeder::class,
        ]);
    }
}
