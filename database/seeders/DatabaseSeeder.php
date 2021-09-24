<?php

namespace Database\Seeders;

use App\Models\Estate;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

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

        $this->call([
            AdminSeeder::class,
            EstateAdminSeeder::class,
        ]);
    }
}
