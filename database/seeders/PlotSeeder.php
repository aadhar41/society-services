<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Society;
use App\Models\Block;
use App\Models\Plot;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\DB;

class PlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET session_replication_role = 'replica';");
        DB::table('plots')->truncate();
        DB::statement("SET session_replication_role = 'origin';");

        \App\Models\Block::all()->each(function ($p) {
            $p->plots()
                ->saveMany(
                    \App\Models\Plot::factory(2)->make()
                );
        });
    }
}
