<?php

namespace App\Domain\Accounting\Controllers;

use App\Domain\Accounting\Models\FinancialYear;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FinancialYearController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => FinancialYear::all()
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_current' => 'nullable|boolean',
        ]);

        if ($validated['is_current'] ?? false) {
             FinancialYear::where('is_current', true)->update(['is_current' => false]);
        }

        $fy = FinancialYear::create($validated);

        return response()->json([
            'success' => true,
            'data' => $fy
        ], 201);
    }
}
