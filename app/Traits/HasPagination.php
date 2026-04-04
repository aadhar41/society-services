<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

trait HasPagination
{
    /**
     * Get per-page count from request (capped at 100).
     */
    protected function perPage(): int
    {
        return min((int) request()->input('per_page', 15), 100);
    }

    /**
     * Return a standardised paginated JSON response.
     */
    protected function paginatedResponse(LengthAwarePaginator $paginator, string $key = 'data'): JsonResponse
    {
        return response()->json([
            'success'  => true,
            $key       => $paginator->items(),
            'meta'     => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
            ],
        ]);
    }
}
