<?php

namespace App\Domain\Society\Controllers;

use App\Domain\Society\Models\Society;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SocietyController extends Controller
{
    /**
     * Display a listing of societies.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if ($user->is_superadmin) {
            $societies = Society::all();
        } else {
            $societies = $user->societies;
        }

        return response()->json([
            'success' => true,
            'data' => $societies
        ]);
    }

    /**
     * Store a newly created society.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_no' => 'nullable|string|max:100',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'pincode' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        $user = $request->user();

        if (!$user->canCreateMoreSocieties()) {
            return response()->json([
                'success' => false,
                'message' => 'Society limit exceeded for your current license.',
            ], 403);
        }

        $society = Society::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Society created successfully.',
            'data' => $society
        ], 201);
    }

    /**
     * Display the specified society.
     */
    public function show(Society $society): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $society
        ]);
    }

    /**
     * Update the specified society.
     */
    public function update(Request $request, Society $society): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'registration_no' => 'nullable|string|max:100',
            'address_line_1' => 'sometimes|required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'sometimes|required|string|max:100',
            'state' => 'sometimes|required|string|max:100',
            'country' => 'sometimes|required|string|max:100',
            'pincode' => 'sometimes|required|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        $society->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Society updated successfully.',
            'data' => $society
        ]);
    }

    /**
     * Remove the specified society.
     */
    public function destroy(Society $society): JsonResponse
    {
        $society->delete();
        return response()->json([
            'success' => true,
            'message' => 'Society deleted successfully.'
        ]);
    }
}
