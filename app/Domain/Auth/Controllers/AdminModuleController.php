<?php

namespace App\Domain\Auth\Controllers;

use App\Models\Module;
use App\Models\SystemRole;
use App\Models\Society;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class AdminModuleController extends Controller
{
    /**
     * List all modules and their global role-based statuses.
     */
    public function index(): JsonResponse
    {
        $modules = Module::all();
        $roles = SystemRole::with('modules')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'modules' => $modules,
                'roles' => $roles
            ]
        ]);
    }

    /**
     * Toggle a module globally.
     */
    public function toggleGlobal(Request $request, Module $module): JsonResponse
    {
        $module->update(['is_active' => !$module->is_active]);
        return response()->json(['success' => true, 'is_active' => $module->is_active]);
    }

    /**
     * Toggle a module for a specific role.
     */
    public function toggleRole(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:erp_roles,id',
            'module_id' => 'required|exists:erp_modules,id',
            'is_enabled' => 'required|boolean'
        ]);

        DB::table('erp_role_modules')->updateOrInsert(
            ['role_id' => $validated['role_id'], 'module_id' => $validated['module_id']],
            ['is_enabled' => $validated['is_enabled'], 'updated_at' => now()]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Get module overrides for a specific society.
     */
    public function societyModules(Society $society): JsonResponse
    {
        $overrides = DB::table('erp_society_modules')
            ->where('society_id', $society->id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $overrides
        ]);
    }

    /**
     * Toggle a module for a specific society.
     */
    public function toggleSociety(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'society_id' => 'required|exists:erp_societies,id',
            'module_id' => 'required|exists:erp_modules,id',
            'is_enabled' => 'required|boolean'
        ]);

        DB::table('erp_society_modules')->updateOrInsert(
            ['society_id' => $validated['society_id'], 'module_id' => $validated['module_id']],
            ['is_enabled' => $validated['is_enabled'], 'updated_at' => now()]
        );

        return response()->json(['success' => true]);
    }
}
