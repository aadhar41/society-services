<?php

namespace App\Domain\Auth\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * POST /api/v2/auth/register
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * POST /api/v2/auth/login
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'user' => $user->load('societies'),
            'token' => $token,
        ]);
    }

    /**
     * POST /api/v2/admin/login
     */
    public function adminLogin(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $user = Auth::user();

        if (!$user->is_superadmin) {
            Auth::logout();
            return response()->json([
                'message' => 'Unauthorized. Not a Super Admin.',
            ], 403);
        }

        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'message' => 'Admin login successful.',
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * POST /api/v2/auth/login/otp
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        // TODO: Integrate with SMS gateway (MSG91, Twilio, etc.)
        // For now, generate and store OTP
        $otp = rand(100000, 999999);

        // Store OTP in cache with 5-min expiry
        cache()->put("otp:{$request->phone}", $otp, 300);

        return response()->json([
            'message' => 'OTP sent successfully.',
            'debug_otp' => app()->isLocal() ? $otp : null, // Only in dev
        ]);
    }

    /**
     * POST /api/v2/auth/login/otp/verify
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'otp' => 'required|string|size:6',
        ]);

        $cachedOtp = cache()->get("otp:{$request->phone}");

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return response()->json([
                'message' => 'Invalid or expired OTP.',
            ], 401);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json([
                'message' => 'No account found with this phone number.',
            ], 404);
        }

        $user->update(['phone_verified_at' => now()]);
        cache()->forget("otp:{$request->phone}");

        $token = $user->createToken('otp-auth')->plainTextToken;

        return response()->json([
            'message' => 'OTP verified successfully.',
            'user' => $user->load('societies'),
            'token' => $token,
        ]);
    }

    /**
     * POST /api/v2/auth/logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * GET /api/v2/auth/me
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user()->load('societies'),
        ]);
    }

    /**
     * POST /api/v2/auth/password/forgot
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // TODO: Send password reset email
        // Password::sendResetLink($request->only('email'));

        return response()->json([
            'message' => 'Password reset link sent to your email.',
        ]);
    }

    /**
     * POST /api/v2/auth/password/reset
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // TODO: Implement password reset with token verification

        return response()->json([
            'message' => 'Password reset successfully.',
        ]);
    }
}
