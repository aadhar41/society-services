<?php

namespace App\Domain\Society\Controllers;

use App\Domain\Society\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UnitController extends Controller
{
    /**
     * Display a listing of units.
     */
    public function index(Request $request): JsonResponse
    {
        $units = Unit::with(['wing', 'floor', 'currentOwner', 'currentTenant'])
            ->when($request->wing_id, fn($q, $id) => $q->where('wing_id', $id))
            ->when($request->unit_type, fn($q, $t) => $q->where('unit_type', $t))
            ->get();

        return response()->json([
            'success' => true,
            'data' => $units
        ]);
    }

    /**
     * Store a newly created unit.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'wing_id' => 'required|exists:wings,id',
            'floor_id' => 'nullable|exists:floors,id',
            'unit_number' => 'required|string|max:20|unique:units,unit_number,NULL,id,wing_id,' . $request->wing_id,
            'unit_type' => 'required|in:flat,shop,office,residential,commercial,other',
            'area_sqft' => 'nullable|numeric|min:0',
            'parking_count' => 'nullable|integer|min:0',
            'intercom_no' => 'nullable|string|max:20',
            'status' => 'nullable|boolean',
        ]);

        $unit = Unit::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Unit created successfully.',
            'data' => $unit
        ], 201);
    }

    /**
     * Display the specified unit.
     */
    public function show(Unit $unit): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $unit->load(['wing', 'floor', 'members', 'parkingSlots'])
        ]);
    }

    /**
     * Update the specified unit.
     */
    public function update(Request $request, Unit $unit): JsonResponse
    {
        $validated = $request->validate([
            'wing_id' => 'sometimes|required|exists:wings,id',
            'floor_id' => 'nullable|exists:floors,id',
            'unit_number' => 'sometimes|required|string|max:20|unique:units,unit_number,' . $unit->id . ',id,wing_id,' . ($request->wing_id ?? $unit->wing_id),
            'unit_type' => 'sometimes|required|in:flat,shop,office,residential,commercial,other',
            'area_sqft' => 'nullable|numeric|min:0',
            'parking_count' => 'nullable|integer|min:0',
            'intercom_no' => 'nullable|string|max:20',
            'status' => 'nullable|boolean',
        ]);

        $unit->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Unit updated successfully.',
            'data' => $unit
        ]);
    }

    /**
     * Remove the specified unit.
     */
    public function destroy(Unit $unit): JsonResponse
    {
        $unit->delete();
        return response()->json([
            'success' => true,
            'message' => 'Unit deleted successfully.'
        ]);
    }
}
