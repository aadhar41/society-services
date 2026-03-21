<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuickStartSeeder extends Seeder
{
    public function run(): void
    {
        $societyUuid = Str::uuid();
        $societyId = DB::table('erp_societies')->insertGetId([
            'uuid' => $societyUuid,
            'name' => 'Gaur Enclave',
            'registration_no' => 'REG12345',
            'address_line_1' => '123 Skyview Ave',
            'city' => 'Tech City',
            'state' => 'UP',
            'pincode' => '201301',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $wingId = DB::table('wings')->insertGetId([
            'uuid' => Str::uuid(),
            'society_id' => $societyId,
            'name' => 'Wing A',
            'code' => 'A',
            'total_floors' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $unitId = DB::table('units')->insertGetId([
            'uuid' => Str::uuid(),
            'society_id' => $societyId,
            'wing_id' => $wingId,
            'unit_number' => '101',
            'unit_type' => 'flat',
            'area_sqft' => 1200,
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $memberId = DB::table('members')->insertGetId([
            'uuid' => Str::uuid(),
            'society_id' => $societyId,
            'unit_id' => $unitId,
            'name' => 'Aadhar Gaur',
            'email' => 'aadhargaur41@gmail.com',
            'phone' => '9999988888',
            'member_type' => 'owner',
            'is_primary' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('QuickStart Data Seeded successfully!');
    }
}
