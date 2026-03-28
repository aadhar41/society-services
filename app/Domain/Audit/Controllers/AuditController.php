<?php

namespace App\Domain\Audit\Controllers;

use App\Domain\Audit\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuditController extends Controller
{
    /**
     * GET /api/v2/audit-logs
     */
    public function index(Request $request): JsonResponse
    {
        $logs = ActivityLog::with('user')
            ->when($request->user_id, fn($q, $id) => $q->where('user_id', $id))
            ->when($request->module, fn($q, $m) => $q->where('log_name', $m))
            ->when($request->event, fn($q, $e) => $q->where('description', $e))
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 50);

        return response()->json($logs);
    }
}
