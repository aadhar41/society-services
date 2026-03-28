<?php

namespace App\Domain\Society\Controllers;

use App\Domain\Society\Models\Society;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminSocietyController extends Controller
{
    /**
     * Display all societies in the system.
     */
    public function index(): JsonResponse
    {
        $societies = Society::withCount(['users', 'wings', 'units'])->get();
        return response()->json([
            'success' => true,
            'data' => $societies
        ]);
    }

    /**
     * Store a new society.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address_line_1' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'pincode' => 'required|string',
        ]);

        $society = Society::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Society created successfully.',
            'data' => $society
        ], 201);
    }

    /**
     * Display details of a specific society.
     */
    public function show(Society $society): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $society->load(['users', 'wings.floors.units'])
        ]);
    }

    /**
     * Update society details.
     */
    public function update(Request $request, Society $society): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'address_line_1' => 'string',
            'city' => 'string',
            'state' => 'string',
            'country' => 'string',
            'pincode' => 'string',
        ]);

        $society->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Society updated successfully.',
            'data' => $society
        ]);
    }

    /**
     * Delete a society (Hard Delete for system admins).
     */
    public function destroy(Society $society): JsonResponse
    {
        // Note: SoftDeletes is used in the model, but system admin might want hard delete?
        // For now, we use standard delete (which is soft delete if trait is present).
        $society->delete();

        return response()->json([
            'success' => true,
            'message' => 'Society deleted successfully.',
        ]);
    }
}
