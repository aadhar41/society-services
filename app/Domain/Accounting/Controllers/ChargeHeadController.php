<?php

namespace App\Domain\Accounting\Controllers;

use App\Domain\Accounting\Models\ChargeHead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ChargeHeadController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => ChargeHead::all()
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric',
            'charge_type' => 'required|string',
            'billing_cycle' => 'required|string',
        ]);

        $chargeHead = ChargeHead::create($validated);

        return response()->json([
            'success' => true,
            'data' => $chargeHead
        ], 201);
    }
}
