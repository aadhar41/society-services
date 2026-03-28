<?php

namespace App\Domain\Vendor\Controllers;

use App\Domain\Vendor\Models\Asset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AssetController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Asset::all()
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric',
            'warranty_expiry' => 'nullable|date',
            'status' => 'nullable|string|max:30',
        ]);

        $asset = Asset::create($validated);

        return response()->json([
            'success' => true,
            'data' => $asset
        ], 201);
    }

    public function show(Asset $asset): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $asset
        ]);
    }

    public function update(Request $request, Asset $asset): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'status' => 'sometimes|required|string|max:30',
        ]);

        $asset->update($validated);

        return response()->json([
            'success' => true,
            'data' => $asset
        ]);
    }

    public function destroy(Asset $asset): JsonResponse
    {
        $asset->delete();
        return response()->json([
            'success' => true,
            'message' => 'Asset record deleted.'
        ]);
    }
}
