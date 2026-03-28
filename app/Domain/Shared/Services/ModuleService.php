<?php

namespace App\Domain\Shared\Services;

use App\Models\Module;
use Illuminate\Support\Facades\DB;

class ModuleService
{
    /**
     * Get all enabled modules for a specific society and role.
     */
    public function getEnabledModules(int $societyId, int $roleId): array
    {
        // 1. Get all globally active modules
        $allModules = Module::where('is_active', true)->get();
        $enabledSlugs = [];

        // 2. Get society overrides
        $societyOverrides = DB::table('erp_society_modules')
            ->where('society_id', $societyId)
            ->pluck('is_enabled', 'module_id')
            ->toArray();

        // 3. Get role defaults
        $roleDefaults = DB::table('erp_role_modules')
            ->where('role_id', $roleId)
            ->pluck('is_enabled', 'module_id')
            ->toArray();

        foreach ($allModules as $module) {
            $isEnabled = true;

            // Society override takes precedence
            if (isset($societyOverrides[$module->id])) {
                $isEnabled = (bool) $societyOverrides[$module->id];
            } 
            // Then role default
            elseif (isset($roleDefaults[$module->id])) {
                $isEnabled = (bool) $roleDefaults[$module->id];
            }

            if ($isEnabled) {
                $enabledSlugs[] = $module->slug;
            }
        }

        return $enabledSlugs;
    }
}
