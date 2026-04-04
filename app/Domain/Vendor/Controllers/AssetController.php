<?php

namespace App\Domain\Vendor\Controllers;

use App\Domain\Vendor\Models\Asset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AssetController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $assets = Asset::with('category')
            ->when($request->category_id, fn($q, $id) => $q->where('category_id', $id))
            ->get();

        return response()->json([
            'success' => true,
            'data' => $assets
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category_id' => 'nullable|exists:complaint_categories,id',
            'category' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:100',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric',
            'current_value' => 'nullable|numeric',
            'condition' => 'nullable|in:new,good,fair,poor,disposed',
            'warranty_expires_at' => 'nullable|date',
        ]);

        $asset = Asset::create(array_merge($validated, [
            'category_id' => ($validated['category_id'] ?? null) ?: null,
        ]));

        return response()->json([
            'success' => true,
            'data' => $asset->load('category')
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
            'category_id' => 'nullable|exists:complaint_categories,id',
            'category' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:100',
            'condition' => 'nullable|in:new,good,fair,poor,disposed',
            'current_value' => 'nullable|numeric',
        ]);

        if (array_key_exists('category_id', $validated)) {
            $validated['category_id'] = ($validated['category_id'] ?? null) ?: null;
        }

        $asset->update($validated);

        return response()->json([
            'success' => true,
            'data' => $asset->load('category')
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
