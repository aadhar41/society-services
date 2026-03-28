<?php

namespace App\Domain\Booking\Controllers;

use App\Domain\Booking\Models\Facility;
use App\Domain\Booking\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FacilityController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Facility::where('is_active', true)->get()
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer',
            'booking_fee' => 'nullable|numeric',
            'is_active' => 'nullable|boolean',
        ]);

        $facility = Facility::create($validated);

        return response()->json([
            'success' => true,
            'data' => $facility
        ], 201);
    }

    public function show(Facility $facility): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $facility->load('slots')
        ]);
    }

    public function update(Request $request, Facility $facility): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $facility->update($validated);

        return response()->json([
            'success' => true,
            'data' => $facility
        ]);
    }

    public function destroy(Facility $facility): JsonResponse
    {
        $facility->delete();
        return response()->json([
            'success' => true,
            'message' => 'Facility deleted.'
        ]);
    }

    /**
     * GET /api/v2/facilities/{facility}/availability
     */
    public function availability(Request $request, Facility $facility): JsonResponse
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $date = $request->date;
        $bookedSlots = Booking::where('facility_id', $facility->id)
            ->whereDate('booking_date', $date)
            ->whereIn('status', ['confirmed', 'pending'])
            ->pluck('slot_id')
            ->toArray();

        $allSlots = $facility->slots;
        
        $availability = $allSlots->map(function ($slot) use ($bookedSlots) {
            return [
                'slot_id' => $slot->id,
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'is_available' => !in_array($slot->id, $bookedSlots)
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $availability
        ]);
    }
}
