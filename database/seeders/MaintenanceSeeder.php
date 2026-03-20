<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Society;
use App\Models\Block;
use App\Models\Plot;
use App\Models\Flat;
use App\Models\Maintenance;
use Illuminate\Support\Facades\DB;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET session_replication_role = 'replica';");
        DB::table('maintenances')->truncate();
        DB::statement("SET session_replication_role = 'origin';");

        \App\Models\Flat::all()->each(function ($f) {
            $f->maintenances()
                ->saveMany(
                    \App\Models\Maintenance::factory(3)->make()
                );
        });
    }
}
