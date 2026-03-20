<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Society;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\DB;


class SocietySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET session_replication_role = 'replica';");
        DB::table('societies')->truncate();
        DB::statement("SET session_replication_role = 'origin';");

        \App\Models\Society::factory(5)->create();
    }
}
