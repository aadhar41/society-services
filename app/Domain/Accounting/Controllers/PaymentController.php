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
            'invoice_id' => 'nullable|exists:erp_invoices,id',
            'unit_id' => 'required|exists:erp_units,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'sometimes|date',
            'payment_method' => 'required|in:cash,cheque,upi,neft,rtgs,razorpay,stripe,online',
            'transaction_reference' => 'nullable|string|max:100',
            'cheque_no' => 'nullable|string|max:30',
            'bank_name' => 'nullable|string|max:100',
            'payment_type' => 'nullable|string|max:50',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = $request->user();
        $societyId = $request->header('X-Society-ID') ?? $user->societies()->first()?->id;

        if (!$societyId) {
             return response()->json(['message' => 'No society selected.'], 400);
        }

        // Authorization: Only super-admins can set payment_type.
        if (!$user->is_superadmin) {
            $validated['payment_type'] = 'maintenance';
        }

        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('payment-docs', 'public');
            $validated['attachment_path'] = $path;
        }

        $invoice = null;

        // Logic for Invoice attribution
        if ($request->filled('invoice_id')) {
            $invoice = Invoice::findOrFail($validated['invoice_id']);
        } elseif ($validated['payment_type'] === 'maintenance') {
            // Find the oldest unpaid invoice for this unit ONLY if payment type is maintenance
            $invoice = Invoice::where('unit_id', $validated['unit_id'])
                ->whereIn('status', ['sent', 'partial', 'overdue'])
                ->orderBy('due_date', 'asc')
                ->first();

            // If it's a maintenance payment but no invoice found, we'll record it as a standalone payment
            // but normally maintenance should have an invoice.
        }

        // If we found an invoice, we use the standard recordPayment logic
        if ($invoice) {
            if ($invoice->isPaid()) {
                return response()->json(['message' => 'This invoice is already fully paid.'], 422);
            }

            if ($validated['amount'] > ($invoice->balance_due + 0.01)) {
                return response()->json([
                    'message' => "Payment amount ({$validated['amount']}) exceeds balance due ({$invoice->balance_due}).",
                ], 422);
            }

            try {
                $payment = $this->billingService->recordPayment($invoice, $validated);
                return response()->json([
                    'message' => 'Payment recorded against invoice.',
                    'data' => $payment->load(['invoice', 'unit']),
                ], 201);
            } catch (\DomainException $e) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
        }

        // If no invoice (or specifically a categorized payment), record as Standalone
        try {
            $unit = \App\Domain\Society\Models\Unit::find($validated['unit_id']);
            $validated['member_id'] = $unit->currentOwner?->id ?? $unit->currentTenant?->id;

            $payment = $this->billingService->recordCategorizedPayment($validated, $societyId);

            return response()->json([
                'message' => 'Standalone payment recorded successfully.',
                'data' => $payment->load('unit'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
