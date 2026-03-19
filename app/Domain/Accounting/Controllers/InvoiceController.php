<?php

namespace App\Domain\Accounting\Controllers;

use App\Domain\Accounting\Models\Invoice;
use App\Domain\Accounting\Services\BillingService;
use App\Domain\Society\Models\Society;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class InvoiceController extends Controller
{
    public function __construct(
        private BillingService $billingService
    ) {}

    /**
     * GET /api/v2/accounting/invoices
     */
    public function index(Request $request): JsonResponse
    {
        $invoices = Invoice::with(['unit.wing', 'member', 'items.chargeHead'])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->unit_id, fn($q, $id) => $q->where('unit_id', $id))
            ->when($request->overdue, fn($q) => $q->overdue())
            ->orderByDesc('billing_period_start')
            ->paginate($request->per_page ?? 20);

        return response()->json($invoices);
    }

    /**
     * GET /api/v2/accounting/invoices/{invoice}
     */
    public function show(Invoice $invoice): JsonResponse
    {
        return response()->json([
            'data' => $invoice->load([
                'unit.wing', 'member', 'items.chargeHead',
                'payments', 'journalEntry.lines.account',
            ]),
        ]);
    }

    /**
     * POST /api/v2/accounting/invoices/generate
     *
     * Auto-generate monthly invoices for all units in the current society.
     */
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'billing_month' => 'required|date_format:Y-m',
        ]);

        $societyId = app('current_society_id');
        $society = Society::findOrFail($societyId);
        $billingMonth = Carbon::createFromFormat('Y-m', $request->billing_month);

        try {
            $invoices = $this->billingService->generateMonthlyBills($society, $billingMonth);

            return response()->json([
                'message' => "Generated {$invoices->count()} invoices for {$billingMonth->format('F Y')}.",
                'invoices_count' => $invoices->count(),
                'total_amount' => $invoices->sum('net_amount'),
            ], 201);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
