<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GaneshNagarModuleSeeder extends Seeder
{
    public function run(): void
    {
        $societyId = DB::table('erp_societies')->where('name', 'Ganesh Nagar Residential Society')->value('id');
        if (!$societyId) return;

        $modules = DB::table('erp_modules')->get();
        foreach ($modules as $module) {
            DB::table('erp_society_modules')->updateOrInsert(
                ['society_id' => $societyId, 'module_id' => $module->id],
                ['is_enabled' => true, 'updated_at' => now()]
            );
        }
    }
}
