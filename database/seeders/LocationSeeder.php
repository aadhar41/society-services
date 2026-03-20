<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class LocationSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks for PostgreSQL
        DB::statement("SET session_replication_role = 'replica';");

        // Clear existing data
        City::truncate();
        State::truncate();
        Country::truncate();

        // Seed Country
        $country = Country::create([
            'name' => 'India',
            'countryCode' => 'IN',
            'status' => 'Active'
        ]);

        // Seed States
        $rajasthan = State::create([
            'state_title' => 'Rajasthan',
            'status' => 'Active'
        ]);

        $maharashtra = State::create([
            'state_title' => 'Maharashtra',
            'status' => 'Active'
        ]);

        // Seed Cities
        City::create([
            'state_id' => $rajasthan->id,
            'name' => 'Jaipur',
            'status' => 'Active'
        ]);

        City::create([
            'state_id' => $rajasthan->id,
            'name' => 'Udaipur',
            'status' => 'Active'
        ]);

        City::create([
            'state_id' => $maharashtra->id,
            'name' => 'Mumbai',
            'status' => 'Active'
        ]);

        City::create([
            'state_id' => $maharashtra->id,
            'name' => 'Pune',
            'status' => 'Active'
        ]);

        // Re-enable foreign key checks
        DB::statement("SET session_replication_role = 'origin';");
    }
}
