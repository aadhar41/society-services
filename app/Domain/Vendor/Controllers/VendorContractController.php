<?php

namespace App\Domain\Vendor\Controllers;

use App\Domain\Vendor\Models\Vendor;
use App\Domain\Vendor\Models\VendorContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class VendorContractController extends Controller
{
    public function index(Vendor $vendor): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $vendor->contracts
        ]);
    }

    public function store(Request $request, Vendor $vendor): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'amount' => 'nullable|numeric',
            'status' => 'nullable|string|max:30',
        ]);

        $contract = $vendor->contracts()->create($validated);

        return response()->json([
            'success' => true,
            'data' => $contract
        ], 201);
    }

    public function show(VendorContract $vendorContract): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $vendorContract->load('vendor')
        ]);
    }

    public function update(Request $request, VendorContract $vendorContract): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:100',
            'status' => 'sometimes|required|string|max:30',
        ]);

        $vendorContract->update($validated);

        return response()->json([
            'success' => true,
            'data' => $vendorContract
        ]);
    }

    public function destroy(VendorContract $vendorContract): JsonResponse
    {
        $vendorContract->delete();
        return response()->json([
            'success' => true,
            'message' => 'Contract deleted.'
        ]);
    }
}
