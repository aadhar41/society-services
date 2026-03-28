<?php

namespace App\Domain\Staff\Controllers;

use App\Domain\Staff\Models\Staff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StaffController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $staff = Staff::when($request->category, fn($q, $c) => $q->where('category', $c))
            ->get();

        return response()->json([
            'success' => true,
            'data' => $staff
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'category' => 'required|string|max:50',
            'salary' => 'nullable|numeric',
            'joining_date' => 'nullable|date',
        ]);

        $staff = Staff::create($validated);

        return response()->json([
            'success' => true,
            'data' => $staff
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
            'status' => 'nullable|boolean',
        ]);

        $staff->update($validated);

        return response()->json([
            'success' => true,
            'data' => $staff
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
