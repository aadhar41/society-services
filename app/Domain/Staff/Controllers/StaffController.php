<?php

namespace App\Domain\Staff\Controllers;

use App\Domain\Staff\Models\Staff;
use App\Traits\HasPagination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StaffController extends Controller
{
    use HasPagination;

    /**
     * Display a paginated, searchable listing of staff.
     * Query params: search, category, status, per_page
     */
    public function index(Request $request): JsonResponse
    {
        $staff = Staff::with('category')
            ->when($request->search, fn($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('name', 'ilike', "%{$s}%")
                  ->orWhere('phone', 'ilike', "%{$s}%");
            }))
            ->when($request->role, fn($q, $r) => $q->where('role', $r))
            ->when($request->category_id, fn($q, $id) => $q->where('category_id', $id))
            ->when($request->has('status'), fn($q) => $q->where('status', $request->boolean('status')))
            ->orderBy('name')
            ->paginate($this->perPage());

        return $this->paginatedResponse($staff);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'role' => 'required|string|max:50',
            'category_id' => 'nullable|exists:complaint_categories,id',
            'department' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric',
            'joining_date' => 'nullable|date',
            'status' => 'nullable|boolean',
        ]);

        $staff = Staff::create(array_merge($validated, [
            'category_id' => ($validated['category_id'] ?? null) ?: null,
        ]));

        return response()->json([
            'success' => true,
            'data' => $staff->load('category')
        ], 201);
    }

    public function show(Staff $staff): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $staff->load('attendance')
        ]);
    }

    public function update(Request $request, Staff $staff): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'phone' => 'sometimes|required|string|max:20',
            'role' => 'sometimes|required|string|max:50',
            'category_id' => 'nullable|exists:complaint_categories,id',
            'department' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric',
            'joining_date' => 'nullable|date',
            'status' => 'nullable|boolean',
        ]);

        if (array_key_exists('category_id', $validated)) {
            $validated['category_id'] = ($validated['category_id'] ?? null) ?: null;
        }

        $staff->update($validated);

        return response()->json([
            'success' => true,
            'data' => $staff->load('category')
        ]);
    }

    public function destroy(Staff $staff): JsonResponse
    {
        $staff->delete();
        return response()->json([
            'success' => true,
            'message' => 'Staff record deleted.'
        ]);
    }

    /**
     * POST /api/v2/staff/{staff}/attendance
     */
    public function recordAttendance(Request $request, Staff $staff): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:check_in,check_out',
            'location' => 'nullable|string',
        ]);

        $attendance = $staff->attendance()->create([
            'type' => $validated['type'],
            'timestamp' => now(),
            'location' => $validated['location'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded.',
            'data' => $attendance
        ], 201);
    }
}
