<?php

namespace App\Domain\Complaint\Controllers;

use App\Domain\Complaint\Models\Complaint;
use App\Domain\Member\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $complaints = Complaint::with(['category', 'unit.wing', 'member', 'assignee'])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->priority, fn($q, $p) => $q->where('priority', $p))
            ->when($request->category_id, fn($q, $id) => $q->where('category_id', $id))
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $complaints
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:complaint_categories,id',
            'unit_id' => 'required|exists:units,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        $memberId = Auth::user()->member?->id;

        if (!$memberId) {
            $memberId = Member::where('unit_id', $validated['unit_id'])
                ->where('is_primary', true)
                ->whereNull('move_out_date')
                ->first()?->id;
        }

        if (!$memberId) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot raise complaint: No primary member found for this unit.'
            ], 422);
        }

        $complaint = Complaint::create(array_merge($validated, [
            'status' => 'open',
            'ticket_number' => 'TCK-' . strtoupper(uniqid()),
            'member_id' => $memberId,
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Complaint raised successfully.',
            'data' => $complaint
        ], 201);
    }

    public function show(Complaint $complaint): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $complaint->load(['category', 'unit.wing', 'member', 'assignee', 'comments.user'])
        ]);
    }

    public function update(Request $request, Complaint $complaint): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'sometimes|required|in:open,in_progress,resolved,closed',
            'priority' => 'sometimes|required|in:low,medium,high,urgent',
            'description' => 'sometimes|required|string',
        ]);

        $complaint->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Complaint updated successfully.',
            'data' => $complaint
        ]);
    }

    public function destroy(Complaint $complaint): JsonResponse
    {
        $complaint->delete();
        return response()->json([
            'success' => true,
            'message' => 'Complaint deleted successfully.'
        ]);
    }

    /**
     * POST /api/v2/complaints/{complaint}/comments
     */
    public function addComment(Request $request, Complaint $complaint): JsonResponse
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
            'is_internal' => 'nullable|boolean',
        ]);

        $comment = $complaint->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
            'is_internal' => $validated['is_internal'] ?? false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully.',
            'data' => $comment->load('user')
        ], 201);
    }

    /**
     * PUT /api/v2/complaints/{complaint}/assign
     */
    public function assign(Request $request, Complaint $complaint): JsonResponse
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
        ]);

        $complaint->update([
            'assigned_to' => $validated['staff_id'],
            'status' => 'in_progress'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Complaint assigned successfully.',
            'data' => $complaint->load('assignee')
        ]);
    }
}
