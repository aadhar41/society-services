<?php

namespace App\Domain\Auth\Controllers;

use App\Domain\Shared\Services\ModuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ModuleAccessController extends Controller
{
    protected $moduleService;

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    public function index(Request $request): JsonResponse
    {
        $societyId = $request->header('X-Society-Id');
        if (!$societyId) {
            return response()->json(['success' => false, 'message' => 'No society selected'], 400);
        }

        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        // Super Admins see all active modules regardless of membership/overrides
        if ($user->is_superadmin) {
             return response()->json([
                 'success' => true,
                 'data' => \App\Models\Module::where('is_active', true)->pluck('slug')
             ]);
        }

        // Get user's role in this society
        $membership = $user->societies()->where('erp_societies.id', $societyId)->first();
        if (!$membership) {
             return response()->json(['success' => false, 'message' => 'Access denied to this society'], 403);
        }

        $roleId = $membership->pivot->role_id;
        $modules = $this->moduleService->getEnabledModules($societyId, $roleId);

        return response()->json([
            'success' => true,
            'data' => $modules
        ]);
    }
}
