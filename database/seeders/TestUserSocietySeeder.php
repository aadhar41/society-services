<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Domain\Society\Models\Society;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TestUserSocietySeeder extends Seeder
{
    public function run()
    {
        $user = User::where('email', 'aadhargaur41@gmail.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Aadhar Gaur',
                'email' => 'aadhargaur41@gmail.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Check if erp_societies has data
        $society = DB::table('erp_societies')->first();
        if (!$society) {
            $societyId = DB::table('erp_societies')->insertGetId([
                'uuid' => Str::uuid(),
                'name' => 'Gaur Enclave',
                'registration_no' => 'REG123456',
                'address_line_1' => 'Street 1, Sector 41',
                'city' => 'Noida',
                'state' => 'Uttar Pradesh',
                'country' => 'India',
                'pincode' => '201301',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $societyId = $society->id;
        }

        // Link user to society
        DB::table('society_user')->updateOrInsert(
            ['society_id' => $societyId, 'user_id' => $user->id],
            ['role_id' => 1, 'status' => true, 'joined_at' => now(), 'created_at' => now(), 'updated_at' => now()]
        );
        
        echo "Linked user {$user->email} to society ID {$societyId}\n";
    }
}
