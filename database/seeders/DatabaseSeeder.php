<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chạy RoleSeeder trước
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            FarmSeeder::class,
            GreenhouseSeeder::class,
        ]);
    }
}
