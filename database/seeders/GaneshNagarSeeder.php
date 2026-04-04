<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Society; // I'll check the model name, it should be erp_societies table
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GaneshNagarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Society
        $societyId = DB::table('erp_societies')->insertGetId([
            'uuid' => Str::uuid(),
            'name' => 'Ganesh Nagar Residential Society',
            'registration_no' => 'GN/2022/RAJ/001',
            'address_line_1' => 'Ganesh Nagar',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'country' => 'India',
            'pincode' => '302012',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Create Wing (Blocks)
        $wingId = DB::table('wings')->insertGetId([
            'uuid' => Str::uuid(),
            'society_id' => $societyId,
            'name' => 'Plots Block',
            'code' => 'PB',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Create Units (Plots)
        // Based on JPG filenames, we have roughly 110 plots
        $plots = range(1, 110);
        foreach ($plots as $plotNo) {
            DB::table('units')->insert([
                'uuid' => Str::uuid(),
                'society_id' => $societyId,
                'wing_id' => $wingId,
                'unit_number' => (string)$plotNo,
                'unit_type' => 'plot',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Create Charge Heads (Maintenance)
        $accountsGroupId = DB::table('account_groups')->where('society_id', $societyId)->where('name', 'Income')->value('id');
        if (!$accountsGroupId) {
            $accountsGroupId = DB::table('account_groups')->insertGetId([
                'uuid' => Str::uuid(),
                'society_id' => $societyId,
                'name' => 'Direct Income',
                'code' => 'INC-DIR',
                'nature' => 'income',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $accountId = DB::table('accounts')->insertGetId([
            'uuid' => Str::uuid(),
            'society_id' => $societyId,
            'account_group_id' => $accountsGroupId,
            'name' => 'Monthly Maintenance',
            'code' => 'ACC-MAINT',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('charge_heads')->insert([
            'uuid' => Str::uuid(),
            'society_id' => $societyId,
            'name' => 'Monthly Maintenance',
            'account_id' => $accountId,
            'amount' => 500.00,
            'frequency' => 'monthly',
            'applies_to' => 'all',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. Create Complaint Categories
        $categories = ['Electricity', 'Water', 'Road', 'Security', 'Cleaning'];
        foreach ($categories as $cat) {
            DB::table('complaint_categories')->insert([
                'uuid' => Str::uuid(),
                'society_id' => $societyId,
                'name' => $cat,
                'sla_hours' => 48,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
