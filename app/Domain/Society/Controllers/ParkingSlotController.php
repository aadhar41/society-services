<?php

namespace App\Domain\Society\Controllers;

use App\Domain\Society\Models\ParkingSlot;
use App\Traits\HasPagination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ParkingSlotController extends Controller
{
    use HasPagination;

    /**
     * Display a paginated, searchable listing of parking slots.
     * Query params: search, unit_id, slot_type, status, per_page
     */
    public function index(Request $request): JsonResponse
    {
        $slots = ParkingSlot::with(['unit.wing'])
            ->when($request->search, fn($q, $s) => $q->where('slot_number', 'ilike', "%{$s}%"))
            ->when($request->unit_id, fn($q, $id) => $q->where('unit_id', $id))
            ->when($request->slot_type, fn($q, $t) => $q->where('slot_type', $t))
            ->when($request->has('status'), fn($q) => $q->where('status', $request->boolean('status')))
            ->orderBy('slot_number')
            ->paginate($this->perPage());

        return $this->paginatedResponse($slots);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'unit_id' => 'nullable|exists:units,id',
            'slot_number' => 'required|string|max:20',
            'slot_type' => 'required|in:four_wheeler,two_wheeler,visitor,car,bike,other',
            'location' => 'nullable|string|max:100',
            'status' => 'nullable|boolean',
        ]);

        $slot = ParkingSlot::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Parking slot created successfully.',
            'data' => $slot
        ], 201);
    }

    public function show(ParkingSlot $parkingSlot): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $parkingSlot->load('unit.wing')
        ]);
    }

    public function update(Request $request, ParkingSlot $parkingSlot): JsonResponse
    {
        $validated = $request->validate([
            'unit_id' => 'nullable|exists:units,id',
            'slot_number' => 'sometimes|required|string|max:20',
            'slot_type' => 'sometimes|required|in:four_wheeler,two_wheeler,visitor,car,bike,other',
            'location' => 'nullable|string|max:100',
            'status' => 'nullable|boolean',
        ]);

        $parkingSlot->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Parking slot updated successfully.',
            'data' => $parkingSlot
        ]);
    }

    public function destroy(ParkingSlot $parkingSlot): JsonResponse
    {
        $parkingSlot->delete();
        return response()->json([
            'success' => true,
            'message' => 'Parking slot deleted successfully.'
        ]);
    }
}
