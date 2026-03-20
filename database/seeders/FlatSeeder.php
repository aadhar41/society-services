<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Society;
use App\Models\Block;
use App\Models\Plot;
use App\Models\Flat;
use Illuminate\Support\Facades\DB;

class FlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET session_replication_role = 'replica';");
        DB::table('flats')->truncate();
        DB::statement("SET session_replication_role = 'origin';");

        \App\Models\Plot::all()->each(function ($p) {
            $p->flats()
                ->saveMany(
                    \App\Models\Flat::factory(2)->make()
                );
        });
    }
}
