<?php

namespace Database\Seeders;

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
        $this->call([
            LocationSeeder::class,
            UserSeeder::class,
            SocietySeeder::class,
            BlockSeeder::class,
            PlotSeeder::class,
            FlatSeeder::class,
            MaintenanceSeeder::class,
            ExpenseSeeder::class,
        ]);
    }
}
