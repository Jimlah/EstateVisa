<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => "SuperAdmin",
                'description' => "SuperAdmin"
            ],
            [
                'name' => "Admin",
                'description' => "Admin"
            ],
            [
                'name' => "SubAdmin",
                'description' => "SubAdmin"
            ],
            [
                'name' => "House Owner",
                'description' => "House Owner"
            ],
            [
                'name' => "Worker",
                'description' => "Worker"
            ],
            [
                'name' => "Family",
                'description' => "Family"
            ],
            [
                'name' => "Tenant",
                'description' => "Tenant"
            ]

            ]);
    }
}
