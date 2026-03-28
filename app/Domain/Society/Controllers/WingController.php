<?php

namespace App\Domain\Society\Controllers;

use App\Domain\Society\Models\Wing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WingController extends Controller
{
    /**
     * Display a listing of wings.
     */
    public function index(): JsonResponse
    {
        $wings = Wing::withCount('units')->get();
        return response()->json([
            'success' => true,
            'data' => $wings
        ]);
    }

    /**
     * Store a newly created wing.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:wings,name',
            'code' => 'required|string|max:20|unique:wings,code',
            'total_floors' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|boolean',
        ]);

        $wing = Wing::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Wing created successfully.',
            'data' => $wing
        ], 201);
    }

    /**
     * Display the specified wing.
     */
    public function show(Wing $wing): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $wing->load('units')
        ]);
    }

    /**
     * Update the specified wing.
     */
    public function update(Request $request, Wing $wing): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:100|unique:wings,name,' . $wing->id,
            'code' => 'sometimes|required|string|max:20|unique:wings,code,' . $wing->id,
            'total_floors' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|boolean',
        ]);

        $wing->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Wing updated successfully.',
            'data' => $wing
        ]);
    }

    /**
     * Remove the specified wing.
     */
    public function destroy(Wing $wing): JsonResponse
    {
        $wing->delete();
        return response()->json([
            'success' => true,
            'message' => 'Wing deleted successfully.'
        ]);
    }
}
