<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Society;
use App\Models\Block;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\DB;

class BlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET session_replication_role = 'replica';");
        DB::table('blocks')->truncate();
        DB::statement("SET session_replication_role = 'origin';");

        \App\Models\Society::all()->each(function ($u) {
            $u->blocks()
                ->saveMany(
                    \App\Models\Block::factory(3)->make()
                );
        });
    }
}
