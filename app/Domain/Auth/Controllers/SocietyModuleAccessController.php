<?php

namespace App\Domain\Auth\Controllers;

use App\Domain\Shared\Services\ModuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class SocietyModuleAccessController extends Controller
{
    public function __construct(private ModuleService $moduleService) {}

    /**
     * Get all modules with their per-role enabled state for this society.
     * Used by the Module Access management page (admin only).
     */
    public function index(): JsonResponse
    {
        $societyId = app('current_society_id');
        $data = $this->moduleService->getSocietyRoleModules($societyId);

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Toggle module access for a specific role in this society.
     * Security module cannot be enabled for council/staff (hard restriction).
     */
    public function toggle(Request $request): JsonResponse
    {
        $societyId = app('current_society_id');

        $validated = $request->validate([
            'role_id'    => 'required|exists:erp_roles,id',
            'module_id'  => 'required|exists:erp_modules,id',
            'is_enabled' => 'required|boolean',
        ]);

        // Prevent enabling the security module for non-admin roles
        $roleSlug  = DB::table('erp_roles')->where('id', $validated['role_id'])->value('slug');
        $moduleSlug = DB::table('erp_modules')->where('id', $validated['module_id'])->value('slug');

        if ($moduleSlug === 'security' && $roleSlug !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'The Security & Users module is restricted to Society Administrators only.',
            ], 403);
        }

        DB::table('erp_society_role_modules')->updateOrInsert(
            [
                'society_id' => $societyId,
                'role_id'    => $validated['role_id'],
                'module_id'  => $validated['module_id'],
            ],
            [
                'is_enabled'  => $validated['is_enabled'],
                'updated_at'  => now(),
                'created_at'  => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Module access updated successfully.',
        ]);
    }
}
