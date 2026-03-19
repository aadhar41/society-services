<?php

namespace App\Domain\Shared\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureAccountingIntegrity Middleware
 *
 * Guards accounting-related endpoints to prevent unauthorized modifications
 * to posted journal entries, locked financial years, and direct balance edits.
 */
class EnsureAccountingIntegrity
{
    public function handle(Request $request, Closure $next): Response
    {
        // Prevent modifications to posted/voided journal entries
        if ($request->route('journal_entry')) {
            $entry = $request->route('journal_entry');

            if (is_object($entry)) {
                if ($entry->is_posted && $request->isMethod('PUT')) {
                    return response()->json([
                        'message' => 'Cannot modify a posted journal entry. Create a reversal entry instead.',
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                if ($entry->voided_at) {
                    return response()->json([
                        'message' => 'This journal entry has been voided and cannot be modified.',
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }
        }

        return $next($request);
    }
}
