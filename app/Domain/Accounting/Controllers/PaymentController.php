<?php

namespace App\Domain\Accounting\Controllers;

use App\Domain\Accounting\Models\Invoice;
use App\Domain\Accounting\Models\Payment;
use App\Domain\Accounting\Services\BillingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PaymentController extends Controller
{
    public function __construct(
        private BillingService $billingService
    ) {}

    /**
     * GET /api/v2/accounting/payments
     */
    public function index(Request $request): JsonResponse
    {
        $payments = Payment::with(['invoice', 'unit.wing', 'member'])
            ->when($request->invoice_id, fn($q, $id) => $q->where('invoice_id', $id))
            ->when($request->payment_method, fn($q, $m) => $q->where('payment_method', $m))
            ->when($request->start_date, fn($q, $d) => $q->where('payment_date', '>=', $d))
            ->when($request->end_date, fn($q, $d) => $q->where('payment_date', '<=', $d))
            ->orderByDesc('payment_date')
            ->paginate($request->per_page ?? 20);

        return response()->json($payments);
    }

    /**
     * POST /api/v2/accounting/payments
     *
     * Record a payment against an invoice.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'sometimes|date',
            'payment_method' => 'required|in:cash,cheque,upi,neft,rtgs,razorpay,stripe',
            'transaction_reference' => 'nullable|string|max:100',
            'cheque_no' => 'nullable|string|max:30',
            'bank_name' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);

        if ($invoice->isPaid()) {
            return response()->json([
                'message' => 'This invoice is already fully paid.',
            ], 422);
        }

        if ($validated['amount'] > $invoice->balance_due) {
            return response()->json([
                'message' => "Payment amount ({$validated['amount']}) exceeds balance due ({$invoice->balance_due}).",
            ], 422);
        }

        try {
            $payment = $this->billingService->recordPayment($invoice, $validated);

            return response()->json([
                'message' => 'Payment recorded successfully.',
                'data' => $payment->load('invoice'),
                'receipt_number' => $payment->receipt_number,
            ], 201);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
