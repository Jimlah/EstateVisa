<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Estate;
use Illuminate\Database\Seeder;
use Database\Seeders\HouseSeeder;
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
        // User::factory()->create([
        //     'email' => 'prohaska.jayde@example.net'
        // ]);

        // Profile::factory()->create();

        // Profile::factory(10)->create();

        Artisan::call('passport:install');
        $this->command->info('Passport installed successfully.');

        $this->call([
            AdminSeeder::class,
            EstateSeeder::class,
            EstateAdminSeeder::class,
            HouseTypeSeeder::class,
            HouseSeeder::class,
        ]);
        $this->command->info('Seeded: Admin, Estate, EstateAdmin, HouseType, EstateHouse');

        User::all()->first()->update([
            'email' => 'superadmin@admin.com',
            'password' => bcrypt('password')
        ]);
        $this->command->info('Created Super Admin superadmin@admin.com');

        Admin::find(1)->user()->update([
            'email' => 'admin@admin.com',
            'password' => bcrypt('password')
        ]);
        $this->command->info('Created Admin admin@admin.com');

        Estate::find(1)->admins()->first()->user()->update([
            'email' => 'superadmin@estate.com',
            'password' => bcrypt('password')
        ]);
        $this->command->info('Created Estate Super Admin superadmin@estate.com');

        Estate::find(1)->admins()->latest()->first()->user()->update([
            'email' => 'admin@estate.com',
            'password' => bcrypt('password')
        ]);
        $this->command->info('Created Estate Admin admin@estate.com');
    }
}
