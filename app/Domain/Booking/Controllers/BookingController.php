<?php

namespace App\Domain\Booking\Controllers;

use App\Domain\Booking\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $bookings = Booking::with(['facility', 'unit.wing', 'member'])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->date, fn($q, $d) => $q->whereDate('booking_date', $d))
            ->orderByDesc('booking_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'unit_id' => 'required|exists:units,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'slot_id' => 'nullable|exists:facility_slots,id',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if already booked
        $exists = Booking::where('facility_id', $validated['facility_id'])
            ->whereDate('booking_date', $validated['booking_date'])
            ->where('slot_id', $validated['slot_id'])
            ->whereIn('status', ['confirmed', 'pending'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'This slot is already booked for the selected date.'
            ], 422);
        }

        $booking = Booking::create(array_merge($validated, [
            'status' => 'pending',
            'member_id' => Auth::user()->member?->id ?? null,
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Booking request submitted.',
            'data' => $booking
        ], 201);
    }

    public function show(Booking $booking): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $booking->load(['facility', 'unit.wing', 'member', 'payment'])
        ]);
    }

    public function update(Request $request, Booking $booking): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'sometimes|required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string|max:500',
        ]);

        $booking->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Booking updated.',
            'data' => $booking
        ]);
    }

    public function destroy(Booking $booking): JsonResponse
    {
        $booking->delete();
        return response()->json([
            'success' => true,
            'message' => 'Booking deleted.'
        ]);
    }
}
