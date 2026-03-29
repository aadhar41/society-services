<?php

namespace App\Domain\Vendor\Controllers;

use App\Domain\Vendor\Models\Vendor;
use App\Traits\HasPagination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class VendorController extends Controller
{
    use HasPagination;

    /**
     * Display a paginated, searchable listing of vendors.
     * Query params: search, status, per_page
     */
    public function index(Request $request): JsonResponse
    {
        $vendors = Vendor::when($request->search, fn($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('name', 'ilike', "%{$s}%")
                  ->orWhere('company', 'ilike', "%{$s}%")
                  ->orWhere('phone', 'ilike', "%{$s}%");
            }))
            ->when($request->has('status'), fn($q) => $q->where('status', $request->boolean('status')))
            ->orderBy('name')
            ->paginate($this->perPage());

        return $this->paginatedResponse($vendors);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'contact_person' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'category' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        $vendor = Vendor::create($validated);

        return response()->json([
            'success' => true,
            'data' => $vendor
        ], 201);
    }

    public function show(Vendor $vendor): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $vendor->load('contracts')
        ]);
    }

    public function update(Request $request, Vendor $vendor): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'phone' => 'sometimes|required|string|max:20',
            'status' => 'nullable|boolean',
        ]);

        $vendor->update($validated);

        return response()->json([
            'success' => true,
            'data' => $vendor
        ]);
    }

    public function destroy(Vendor $vendor): JsonResponse
    {
        $vendor->delete();
        return response()->json([
            'success' => true,
            'message' => 'Vendor record deleted.'
        ]);
    }
}
