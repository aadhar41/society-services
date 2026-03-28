<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GaneshNagarAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'ganesh_admin@example.com'],
            ['name' => 'Ganesh Admin', 'password' => Hash::make('password')]
        );

        $societyId = DB::table('erp_societies')->where('name', 'Ganesh Nagar Residential Society')->value('id');
        $roleId = DB::table('erp_roles')->where('name', 'Admin')->value('id');

        if ($societyId && $roleId) {
            DB::table('society_user')->updateOrInsert(
                ['user_id' => $user->id, 'society_id' => $societyId],
                [
                    'role_id' => $roleId, 
                    'joined_at' => now(), 
                    'status' => true, 
                    'created_at' => now(), 
                    'updated_at' => now()
                ]
            );
        }
    }
}
