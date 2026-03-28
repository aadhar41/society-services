<?php

namespace App\Domain\Accounting\Controllers;

use App\Domain\Accounting\Models\Account;
use App\Domain\Accounting\Models\JournalEntryLine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LedgerController extends Controller
{
    public function show(Account $account, Request $request): JsonResponse
    {
        $lines = JournalEntryLine::with('entry')
            ->where('account_id', $account->id)
            ->when($request->from_date, fn($q, $d) => $q->whereHas('entry', fn($eq) => $eq->where('date', '>=', $d)))
            ->when($request->to_date, fn($q, $d) => $q->whereHas('entry', fn($eq) => $eq->where('date', '<=', $d)))
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'account' => $account,
                'entries' => $lines
            ]
        ]);
    }
}
