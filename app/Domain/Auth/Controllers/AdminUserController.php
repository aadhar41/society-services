<?php

namespace App\Domain\Auth\Controllers;

use App\Models\User;
use App\Models\License;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index(): JsonResponse
    {
        $users = User::with(['license', 'societies'])->withCount('societies')->get();
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Store a new user.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)],
            'is_superadmin' => 'boolean',
            'license_id' => 'nullable|exists:licenses,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_superadmin' => $validated['is_superadmin'] ?? false,
            'license_id' => $validated['license_id'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'data' => $user
        ], 201);
    }

    /**
     * Update user license or superadmin status.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'is_superadmin' => 'boolean',
            'license_id' => 'nullable|exists:licenses,id',
        ]);

        // Validation: If license is being changed, check if current societies exceed new limit
        if (isset($validated['license_id']) && $validated['license_id'] !== $user->license_id) {
            $newLicense = License::find($validated['license_id']);
            $currentCount = $user->societies()->count();
            if ($newLicense && $currentCount > $newLicense->max_societies) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot change license. User has {$currentCount} societies, but the selected license only allows {$newLicense->max_societies}.",
                ], 422);
            }
        }

        // Validation: If license is being removed (set to No License), ensure they have at least 1 society or will have one
        if (array_key_exists('license_id', $validated) && is_null($validated['license_id'])) {
            $currentCount = $user->societies()->count();
            if ($currentCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Users with No License must be assigned to at least one society.",
                ], 422);
            }
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'data' => $user->load('license')
        ]);
    }

    /**
     * Assign user to a society with a role.
     */
    public function assignToSociety(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'society_id' => 'required|exists:erp_societies,id',
            'role_id' => 'required|in:1', // Only Society Administrator (1) can be assigned via Master Admin
        ]);

        // Validation: Check license limits
        $currentCount = $user->societies()->count();
        if ($user->license) {
            if ($currentCount >= $user->license->max_societies) {
                return response()->json([
                    'success' => false,
                    'message' => "License limit reached. This user can only be assigned to {$user->license->max_societies} society(ies).",
                ], 422);
            }
        } else {
            // For No License, let's assume a limit of 1 for now as per requirements
            if ($currentCount >= 1) {
                return response()->json([
                    'success' => false,
                    'message' => "Users with No License can only be assigned to 1 society.",
                ], 422);
            }
        }

        $user->societies()->syncWithoutDetaching([
            $validated['society_id'] => [
                'role_id' => $validated['role_id'],
                'joined_at' => now(),
                'status' => true
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User assigned to society successfully.',
        ]);
    }

    /**
     * Unassign user from a society.
     */
    public function unassignFromSociety(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'society_id' => 'required|exists:erp_societies,id',
        ]);

        // Validation: Prevent unassigning last society for No License users
        if (is_null($user->license_id)) {
            $currentCount = $user->societies()->count();
            if ($currentCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => "Users with No License must have at least one assigned society.",
                ], 422);
            }
        }

        $user->societies()->detach($validated['society_id']);

        return response()->json([
            'success' => true,
            'message' => 'User unassigned from society successfully.',
        ]);
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'password' => ['required', \Illuminate\Validation\Rules\Password::min(8)],
        ]);

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.',
        ]);
    }

    /**
     * Remove a user.
     */
    public function destroy(User $user): JsonResponse
    {
        if ($user->is_superadmin && User::where('is_superadmin', true)->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete the last Super Admin.',
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.',
        ]);
    }
}
