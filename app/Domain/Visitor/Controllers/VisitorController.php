<?php

namespace App\Domain\Visitor\Controllers;

use App\Domain\Visitor\Models\Visitor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class VisitorController extends Controller
{
    /**
     * GET /api/v2/visitors
     */
    public function index(Request $request): JsonResponse
    {
        $visitors = Visitor::with(['unit.wing', 'preApprovedBy'])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->date, fn($q, $d) => $q->whereDate('check_in', $d))
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $visitors
        ]);
    }

    /**
     * POST /api/v2/visitors/pre-approve
     */
    public function preApprove(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'purpose' => 'nullable|string|max:100',
            'expected_at' => 'required|date',
        ]);

        $visitor = Visitor::create(array_merge($validated, [
            'status' => 'approved',
            'pass_code' => strtoupper(substr(uniqid(), -6)),
            'pre_approved_by' => \Illuminate\Support\Facades\Auth::id(),
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Visitor pre-approved successfully.',
            'data' => $visitor
        ], 201);
    }

    /**
     * POST /api/v2/visitors/check-in
     */
    public function checkIn(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'visitor_id' => 'sometimes|exists:visitors,id',
            'pass_code' => 'sometimes|string',
            'name' => 'required_without:visitor_id|string',
            'unit_id' => 'required_without:visitor_id|exists:units,id',
            'photo' => 'nullable|string', // Base64 or URL
        ]);

        if (isset($validated['visitor_id'])) {
            $visitor = Visitor::findOrFail($validated['visitor_id']);
        } elseif (isset($validated['pass_code'])) {
            $visitor = Visitor::where('pass_code', $validated['pass_code'])->firstOrFail();
        } else {
            $visitor = Visitor::create([
                'unit_id' => $validated['unit_id'],
                'name' => $validated['name'],
                'status' => 'pending',
            ]);
        }

        $visitor->update([
            'status' => 'checked_in',
            'check_in' => now(),
            'photo' => $validated['photo'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Visitor checked in successfully.',
            'data' => $visitor
        ]);
    }

    /**
     * PUT /api/v2/visitors/{visitor}/check-out
     */
    public function checkOut(Visitor $visitor): JsonResponse
    {
        $visitor->update([
            'status' => 'checked_out',
            'check_out' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Visitor checked out successfully.',
            'data' => $visitor
        ]);
    }
}
