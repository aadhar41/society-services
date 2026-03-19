<?php

namespace App\Domain\Accounting\Controllers;

use App\Domain\Accounting\Models\JournalEntry;
use App\Domain\Accounting\Services\AccountingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class JournalEntryController extends Controller
{
    public function __construct(
        private AccountingService $accountingService
    ) {}

    /**
     * GET /api/v2/accounting/journal-entries
     */
    public function index(Request $request): JsonResponse
    {
        $entries = JournalEntry::with('lines.account', 'createdByUser')
            ->when($request->entry_type, fn($q, $type) => $q->where('entry_type', $type))
            ->when($request->is_posted !== null, fn($q) => $q->where('is_posted', $request->boolean('is_posted')))
            ->when($request->start_date, fn($q, $d) => $q->where('date', '>=', $d))
            ->when($request->end_date, fn($q, $d) => $q->where('date', '<=', $d))
            ->orderByDesc('date')
            ->paginate($request->per_page ?? 20);

        return response()->json($entries);
    }

    /**
     * POST /api/v2/accounting/journal-entries
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'narration' => 'required|string|max:500',
            'entry_type' => 'sometimes|string|in:manual,billing,payment,receipt,auto',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.debit' => 'required|numeric|min:0',
            'lines.*.credit' => 'required|numeric|min:0',
            'lines.*.narration' => 'nullable|string|max:255',
        ]);

        $validated['society_id'] = app('current_society_id');

        try {
            $entry = $this->accountingService->createJournalEntry($validated);

            return response()->json([
                'message' => 'Journal entry created successfully.',
                'data' => $entry->load('lines.account'),
            ], 201);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * GET /api/v2/accounting/journal-entries/{journal_entry}
     */
    public function show(JournalEntry $journalEntry): JsonResponse
    {
        return response()->json([
            'data' => $journalEntry->load('lines.account', 'createdByUser', 'approvedByUser'),
        ]);
    }

    /**
     * POST /api/v2/accounting/journal-entries/{journal_entry}/post
     */
    public function post(JournalEntry $journalEntry): JsonResponse
    {
        try {
            $entry = $this->accountingService->postEntry($journalEntry);

            return response()->json([
                'message' => 'Journal entry posted successfully.',
                'data' => $entry,
            ]);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * POST /api/v2/accounting/journal-entries/{journal_entry}/void
     */
    public function void(Request $request, JournalEntry $journalEntry): JsonResponse
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            $reversal = $this->accountingService->voidEntry($journalEntry, $request->reason);

            return response()->json([
                'message' => 'Journal entry voided. Reversal entry created.',
                'reversal' => $reversal->load('lines.account'),
            ]);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
