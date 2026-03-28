<?php

namespace App\Domain\Complaint\Controllers;

use App\Domain\Complaint\Models\ComplaintCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ComplaintCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => ComplaintCategory::all()
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        $category = ComplaintCategory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.',
            'data' => $category
        ], 201);
    }

    public function show(ComplaintCategory $complaintCategory): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $complaintCategory
        ]);
    }

    public function update(Request $request, ComplaintCategory $complaintCategory): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'description' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        $complaintCategory->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully.',
            'data' => $complaintCategory
        ]);
    }

    public function destroy(ComplaintCategory $complaintCategory): JsonResponse
    {
        $complaintCategory->delete();
        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully.'
        ]);
    }
}
