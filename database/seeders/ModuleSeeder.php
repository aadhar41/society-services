<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\SystemRole;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            ['name' => 'Meetings', 'slug' => 'meetings', 'description' => 'Schedule and record society meetings.', 'is_active' => true],
            ['name' => 'Asset Management', 'slug' => 'assets', 'description' => 'Inventory and maintenance of assets.', 'is_active' => true],
            ['name' => 'Parking', 'slug' => 'parking', 'description' => 'Manage slots and vehicle tags.', 'is_active' => true],
            ['name' => 'Infrastructure', 'slug' => 'infrastructure', 'description' => 'Wings, Units, and Parking allocations.', 'is_active' => true],
            ['name' => 'Accounting', 'slug' => 'accounting', 'description' => 'Ledger, Invoices, and Payments.', 'is_active' => true],
            ['name' => 'Complaints', 'slug' => 'complaints', 'description' => 'Raise and track maintenance requests.', 'is_active' => true],
            ['name' => 'Visitors', 'slug' => 'visitors', 'description' => 'Pre-approvals and check-in logs.', 'is_active' => true],
            ['name' => 'Facility Booking', 'slug' => 'bookings', 'description' => 'Reserve clubhouse, gym, and pool.', 'is_active' => true],
            ['name' => 'Communication', 'slug' => 'communication', 'description' => 'Notices, Polls, and Announcements.', 'is_active' => true],
            ['name' => 'Members', 'slug' => 'members', 'description' => 'Resident directory and documents.', 'is_active' => true],
            ['name' => 'Staff & Vendors', 'slug' => 'staff', 'description' => 'Manage service providers and staff.', 'is_active' => true],
            ['name' => 'Documents', 'slug' => 'documents', 'description' => 'Society bylaws and certificates.', 'is_active' => true],
        ];

        foreach ($modules as $m) {
            Module::updateOrCreate(['slug' => $m['slug']], $m);
        }

        $roles = [
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Council Member', 'slug' => 'council'],
            ['name' => 'Staff', 'slug' => 'staff'],
        ];

        foreach ($roles as $r) {
            SystemRole::updateOrCreate(['slug' => $r['slug']], $r);
        }

        // Enable all modules for admin role by default
        $adminRole = SystemRole::where('slug', 'admin')->first();
        $allModules = Module::all();
        
        foreach ($allModules as $module) {
            DB::table('erp_role_modules')->updateOrInsert(
                ['role_id' => $adminRole->id, 'module_id' => $module->id],
                ['is_enabled' => true]
            );
        }
    }
}
