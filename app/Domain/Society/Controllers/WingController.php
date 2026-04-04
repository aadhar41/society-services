<?php

namespace App\Domain\Society\Controllers;

use App\Domain\Society\Models\Wing;
use App\Traits\HasPagination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WingController extends Controller
{
    use HasPagination;

    /**
     * Display a paginated, searchable listing of wings.
     * Query params: search, status, per_page
     */
    public function index(Request $request): JsonResponse
    {
        $wings = Wing::withCount('units')
            ->when($request->search, fn($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('name', 'ilike', "%{$s}%")
                  ->orWhere('code', 'ilike', "%{$s}%");
            }))
            ->when($request->has('status'), fn($q) => $q->where('status', $request->boolean('status')))
            ->orderBy('name')
            ->paginate($this->perPage());

        return $this->paginatedResponse($wings);
    }

    /**
     * Store a newly created wing.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:wings,name',
            'code' => 'required|string|max:50|unique:wings,code',
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
            'code' => 'sometimes|required|string|max:50|unique:wings,code,' . $wing->id,
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
