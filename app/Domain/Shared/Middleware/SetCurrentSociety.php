<?php

namespace App\Domain\Shared\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SetCurrentSociety Middleware
 *
 * Resolves the current society from the request (header, route param, or session)
 * and binds it to the application container for global scope filtering.
 *
 * Usage in routes:
 *   Route::middleware('set.society')->group(...)
 *
 * The society can be set via:
 *   - X-Society-Id header (API)
 *   - Route parameter {society}
 *   - Session 'current_society_id' (Web)
 */
class SetCurrentSociety
{
    public function handle(Request $request, Closure $next): Response
    {
        $societyId = $this->resolveSocietyId($request);

        if ($societyId) {
            // Verify the user belongs to this society
            $user = $request->user();

            if ($user && !$user->is_superadmin) {
                $belongsToSociety = $user->societies()
                    ->where($user->societies()->getRelated()->getTable() . '.id', $societyId)
                    ->exists();

                if (!$belongsToSociety) {
                    return response()->json([
                        'message' => 'You do not have access to this society.',
                    ], Response::HTTP_FORBIDDEN);
                }
            }

            // Bind current society ID to the container
            app()->instance('current_society_id', (int) $societyId);
        }

        return $next($request);
    }

    /**
     * Resolve the society ID from various sources.
     */
    protected function resolveSocietyId(Request $request): ?int
    {
        // Priority 1: X-Society-Id header (API clients)
        if ($request->hasHeader('X-Society-Id')) {
            return (int) $request->header('X-Society-Id');
        }

        // Priority 2: Route parameter
        if ($request->route('society')) {
            $society = $request->route('society');
            return is_object($society) ? $society->id : (int) $society;
        }

        // Priority 3: Query parameter
        if ($request->has('society_id')) {
            return (int) $request->input('society_id');
        }

        // Priority 4: Session (web)
        if ($request->session() && $request->session()->has('current_society_id')) {
            return (int) $request->session()->get('current_society_id');
        }

        return null;
    }
}
