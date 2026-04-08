<?php

namespace App\Domain\Auth\Controllers;

use App\Models\User;
use App\Models\SystemRole;
use App\Domain\Society\Models\Society;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SocietyUserController extends Controller
{
    /**
     * Get the current society instance from container.
     */
    private function currentSociety(): Society
    {
        $societyId = app('current_society_id');
        return Society::findOrFail($societyId);
    }

    /**
     * List all users belonging to the current society (excluding admin role).
     */
    public function index(): JsonResponse
    {
        $society = $this->currentSociety();

        $users = $society->users()
            ->withPivot('role_id', 'joined_at', 'status')
            ->get()
            ->map(function ($user) {
                $role = SystemRole::find($user->pivot->role_id);
                return [
                    'id'        => $user->id,
                    'name'      => $user->name,
                    'email'     => $user->email,
                    'phone'     => $user->phone,
                    'avatar'    => $user->avatar,
                    'role_id'   => $user->pivot->role_id,
                    'role_name' => $role?->name ?? 'No Role',
                    'role_slug' => $role?->slug,
                    'joined_at' => $user->pivot->joined_at,
                    'status'    => (bool) $user->pivot->status,
                ];
            })
            // Exclude admins (role_id=1) from this panel — they are managed by Super Admin
            ->filter(function ($u) {
                $role = SystemRole::find($u['role_id']);
                return !$role || $role->slug !== 'admin';
            })
            ->values();

        $roles = SystemRole::whereIn('slug', ['council', 'staff'])->get();

        return response()->json([
            'success' => true,
            'data'    => $users,
            'roles'   => $roles,
        ]);
    }

    /**
     * Invite / create a new user and add them to the society.
     */
    public function store(Request $request): JsonResponse
    {
        $society = $this->currentSociety();

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => ['required', Password::min(8)],
            'role_id'  => 'required|exists:erp_roles,id',
        ]);

        // Prevent assigning admin role from SAMS Portal
        $role = SystemRole::findOrFail($validated['role_id']);
        if ($role->slug === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Society Administrator role can only be assigned by Super Admin from the Admin Panel.',
            ], 403);
        }

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        $society->users()->syncWithoutDetaching([
            $user->id => [
                'role_id'   => $validated['role_id'],
                'joined_at' => now(),
                'status'    => true,
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created and added to society successfully.',
            'data'    => [
                'id'        => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'phone'     => $user->phone,
                'role_id'   => $validated['role_id'],
                'role_name' => $role->name,
                'role_slug' => $role->slug,
                'status'    => true,
            ],
        ], 201);
    }

    /**
     * Update a society user's role or status.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $society = $this->currentSociety();

        // Ensure user belongs to this society
        $exists = $society->users()->where('user_id', $user->id)->exists();
        if (!$exists) {
            return response()->json([
                'success' => false,
                'message' => 'User not found in this society.',
            ], 404);
        }

        $validated = $request->validate([
            'role_id' => 'required|exists:erp_roles,id',
            'status'  => 'boolean',
        ]);

        // Prevent assigning admin role from SAMS Portal
        $role = SystemRole::findOrFail($validated['role_id']);
        if ($role->slug === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Society Administrator role can only be assigned by Super Admin.',
            ], 403);
        }

        $society->users()->updateExistingPivot($user->id, [
            'role_id' => $validated['role_id'],
            'status'  => $validated['status'] ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User role updated successfully.',
        ]);
    }

    /**
     * Remove a user from the society (does not delete the system user account).
     */
    public function destroy(User $user): JsonResponse
    {
        $society = $this->currentSociety();

        $exists = $society->users()->where('user_id', $user->id)->exists();
        if (!$exists) {
            return response()->json([
                'success' => false,
                'message' => 'User not found in this society.',
            ], 404);
        }

        $society->users()->detach($user->id);

        return response()->json([
            'success' => true,
            'message' => 'User removed from society successfully.',
        ]);
    }

    /**
     * Get available roles for this panel (council, staff only).
     */
    public function roles(): JsonResponse
    {
        $roles = SystemRole::whereIn('slug', ['council', 'staff'])->get();

        return response()->json([
            'success' => true,
            'data'    => $roles,
        ]);
    }
}
