<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class RegionalDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed India
        Country::updateOrCreate(
            ['countryCode' => 'IN'],
            ['name' => 'India', 'status' => 'Active']
        );

        // 2. Seed Rajasthan and its cities
        $rajasthan = State::updateOrCreate(
            ['state_title' => 'Rajasthan'],
            ['status' => 'Active', 'state_description' => 'Land of Kings']
        );

        $rajasthanCities = [
            'Jaipur', 'Udaipur', 'Jodhpur', 'Kota', 'Ajmer', 'Bikaner', 'Sikar', 'Pali', 'Alwar', 'Bhilwara'
        ];

        foreach ($rajasthanCities as $cityName) {
            City::updateOrCreate(
                ['state_id' => $rajasthan->id, 'name' => $cityName],
                ['status' => 'Active']
            );
        }

        // 3. Seed Maharashtra and its cities
        $maharashtra = State::updateOrCreate(
            ['state_title' => 'Maharashtra'],
            ['status' => 'Active', 'state_description' => 'Gateway to India']
        );

        $maharashtraCities = [
            'Mumbai', 'Pune', 'Nagpur', 'Thane', 'Pimpri-Chinchwad', 'Nashik', 'Kalyan-Dombivli', 'Vasai-Virar', 'Aurangabad', 'Navi Mumbai'
        ];

        foreach ($maharashtraCities as $cityName) {
            City::updateOrCreate(
                ['state_id' => $maharashtra->id, 'name' => $cityName],
                ['status' => 'Active']
            );
        }
    }
}
