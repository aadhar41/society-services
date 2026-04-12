<?php

namespace App\Domain\Shared\Services;

use App\Models\Module;
use Illuminate\Support\Facades\DB;

class ModuleService
{
    /**
     * Get all enabled modules for a specific society and role.
     *
     * Priority (highest → lowest):
     *  1. erp_role_modules (hard role restriction) — if role EXPLICITLY disables a module, always block it.
     *  2. erp_society_role_modules (society-admin override per role)
     *  3. erp_society_modules (society-wide override)
     *  4. erp_role_modules enabled default
     *  5. Global default → enabled
     */
    public function getEnabledModules(int $societyId, int|null $roleId = null): array
    {
        // 1. Get all globally active modules
        $allModules = Module::where('is_active', true)->get();
        $enabledSlugs = [];

        // 2. Society-wide overrides (keyed by module_id → is_enabled)
        $societyOverrides = DB::table('erp_society_modules')
            ->where('society_id', $societyId)
            ->pluck('is_enabled', 'module_id')
            ->toArray();

        // 3. Role global defaults
        $roleDefaults = [];
        if ($roleId !== null) {
            $roleDefaults = DB::table('erp_role_modules')
                ->where('role_id', $roleId)
                ->pluck('is_enabled', 'module_id')
                ->toArray();
        }

        // 4. Per-society, per-role overrides (set by Society Admin)
        $societyRoleOverrides = [];
        if ($roleId !== null) {
            $societyRoleOverrides = DB::table('erp_society_role_modules')
                ->where('society_id', $societyId)
                ->where('role_id', $roleId)
                ->pluck('is_enabled', 'module_id')
                ->toArray();
        }

        foreach ($allModules as $module) {
            $isEnabled = true;

            // Rule 1: If role explicitly DISABLES this module → always blocked (hard restriction).
            // This enforces role-level restrictions (e.g. security ❌ council/staff).
            if (isset($roleDefaults[$module->id]) && !(bool)$roleDefaults[$module->id]) {
                continue; // Hard block — skip this module entirely
            }

            // Rule 2: Society-admin set a per-role override for this society → use it
            if (isset($societyRoleOverrides[$module->id])) {
                $isEnabled = (bool) $societyRoleOverrides[$module->id];
            }
            // Rule 3: Society-wide override
            elseif (isset($societyOverrides[$module->id])) {
                $isEnabled = (bool) $societyOverrides[$module->id];
            }
            // Rule 4: Role global default (enabled case)
            elseif (isset($roleDefaults[$module->id])) {
                $isEnabled = (bool) $roleDefaults[$module->id];
            }
            // Rule 5: Default → enabled

            if ($isEnabled) {
                $enabledSlugs[] = $module->slug;
            }
        }

        return $enabledSlugs;
    }

    /**
     * Get per-society, per-role module access settings for the Module Access management page.
     * Returns all modules with their enabled state for each non-admin role.
     */
    public function getSocietyRoleModules(int $societyId): array
    {
        $allModules = Module::where('is_active', true)->orderBy('name')->get();

        // Get all non-admin roles
        $roles = DB::table('erp_roles')->whereNotIn('slug', ['admin'])->get();

        // Get all society-role overrides for this society
        $overrides = DB::table('erp_society_role_modules')
            ->where('society_id', $societyId)
            ->get()
            ->groupBy('role_id');

        // Get role global defaults
        $roleDefaults = DB::table('erp_role_modules')
            ->get()
            ->groupBy('role_id');

        $result = [];
        foreach ($allModules as $module) {
            $roleAccess = [];
            foreach ($roles as $role) {
                // Check if role is hard-blocked globally
                $roleDefault = ($roleDefaults[$role->id] ?? collect())->firstWhere('module_id', $module->id);

                // Society-role override takes precedence over role default (for enabled cases)
                $societyOverride = ($overrides[$role->id] ?? collect())->firstWhere('module_id', $module->id);

                if ($societyOverride) {
                    $enabled = (bool) $societyOverride->is_enabled;
                } elseif ($roleDefault) {
                    $enabled = (bool) $roleDefault->is_enabled;
                } else {
                    $enabled = true; // default enabled
                }

                $roleAccess[$role->id] = [
                    'role_id'    => $role->id,
                    'role_name'  => $role->name,
                    'role_slug'  => $role->slug,
                    'is_enabled' => $enabled,
                    'has_override' => $societyOverride !== null,
                ];
            }

            $result[] = [
                'module_id'   => $module->id,
                'module_name' => $module->name,
                'module_slug' => $module->slug,
                'roles'       => array_values($roleAccess),
            ];
        }

        return $result;
    }
}
