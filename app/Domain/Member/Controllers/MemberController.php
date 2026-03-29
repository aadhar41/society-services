<?php

namespace App\Domain\Member\Controllers;

use App\Domain\Member\Models\Member;
use App\Domain\Society\Models\Unit;
use App\Traits\HasPagination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    use HasPagination;

    /**
     * Display a paginated, searchable listing of members.
     * Query params: search, unit_id, member_type, per_page
     */
    public function index(Request $request): JsonResponse
    {
        $members = Member::with(['unit.wing', 'user'])
            ->when($request->search, fn($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('name', 'ilike', "%{$s}%")
                  ->orWhere('phone', 'ilike', "%{$s}%")
                  ->orWhere('email', 'ilike', "%{$s}%");
            }))
            ->when($request->unit_id, fn($q, $id) => $q->where('unit_id', $id))
            ->when($request->member_type, fn($q, $t) => $q->where('member_type', $t))
            ->active()
            ->orderBy('name')
            ->paginate($this->perPage());

        return $this->paginatedResponse($members);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'user_id' => 'nullable|exists:users,id',
            'member_type' => 'required|in:owner,tenant,family_member,other',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_primary' => 'nullable|boolean',
            'move_in_date' => 'nullable|date',
        ]);

        $member = Member::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Member added successfully.',
            'data' => $member
        ], 201);
    }

    public function show(Member $member): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $member->load(['unit.wing', 'user', 'documents', 'vehicles'])
        ]);
    }

    public function update(Request $request, Member $member): JsonResponse
    {
        $validated = $request->validate([
            'member_type' => 'sometimes|required|in:owner,tenant,family_member,other',
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_primary' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        $member->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Member updated successfully.',
            'data' => $member
        ]);
    }

    public function destroy(Member $member): JsonResponse
    {
        $member->delete();
        return response()->json([
            'success' => true,
            'message' => 'Member record deleted.'
        ]);
    }

    /**
     * POST /api/v2/members/{member}/move-out
     */
    public function moveOut(Request $request, Member $member): JsonResponse
    {
        $validated = $request->validate([
            'move_out_date' => 'required|date',
        ]);

        $member->update([
            'move_out_date' => $validated['move_out_date'],
            'status' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Member move-out recorded successfully.'
        ]);
    }

    /**
     * POST /api/v2/members/{member}/documents
     */
    public function uploadDocument(Request $request, Member $member): JsonResponse
    {
        $request->validate([
            'document' => 'required|file|max:5120',
            'title' => 'required|string|max:255',
            'category' => 'nullable|string',
        ]);

        $path = $request->file('document')->store("members/{$member->id}/docs", 'public');

        $doc = $member->documents()->create([
            'title' => $request->title,
            'category' => $request->category ?? 'General',
            'file_path' => $path,
            'file_type' => $request->file('document')->getMimeType(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully.',
            'data' => $doc
        ], 201);
    }

    /**
     * GET /api/v2/units/{unit}/members
     */
    public function byUnit(Unit $unit): JsonResponse
    {
        $members = $unit->members()->active()->get();
        return response()->json([
            'success' => true,
            'data' => $members
        ]);
    }
}
