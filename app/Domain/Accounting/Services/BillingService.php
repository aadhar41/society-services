<?php

namespace App\Domain\Accounting\Services;

use App\Domain\Accounting\Models\ChargeHead;
use App\Domain\Accounting\Models\Invoice;
use App\Domain\Accounting\Models\InvoiceItem;
use App\Domain\Accounting\Models\Payment;
use App\Domain\Society\Models\Society;
use App\Domain\Society\Models\Unit;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * BillingService — Invoice generation and payment processing.
 *
 * Handles:
 *   - Monthly invoice generation for all units
 *   - Late fee calculation and application
 *   - Payment recording with automatic journal entries
 *   - Defaulter report generation
 */
class BillingService
{
    public function __construct(
        private AccountingService $accountingService
    ) {}

    /**
     * Generate monthly bills for all units in a society.
     *
     * @return Collection<Invoice>
     */
    public function generateMonthlyBills(Society $society, Carbon $billingMonth): Collection
    {
        return DB::transaction(function () use ($society, $billingMonth) {
            $units = Unit::withoutGlobalScopes()
                ->where('society_id', $society->id)
                ->where('status', true)
                ->with('currentOwner')
                ->get();

            $chargeHeads = ChargeHead::withoutGlobalScopes()
                ->where('society_id', $society->id)
                ->where('is_active', true)
                ->get();

            $invoices = collect();
            $billingStart = $billingMonth->copy()->startOfMonth();
            $billingEnd = $billingMonth->copy()->endOfMonth();

            foreach ($units as $unit) {
                // Check if invoice already exists for this period
                $exists = Invoice::withoutGlobalScopes()
                    ->where('society_id', $society->id)
                    ->where('unit_id', $unit->id)
                    ->where('billing_period_start', $billingStart)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $member = $unit->currentOwner ?? $unit->currentTenant;
                if (!$member) {
                    continue;
                }

                $invoiceNumber = $this->generateInvoiceNumber($society->id, $billingMonth);

                $invoice = Invoice::create([
                    'society_id' => $society->id,
                    'unit_id' => $unit->id,
                    'member_id' => $member->id,
                    'invoice_number' => $invoiceNumber,
                    'financial_year_id' => $society->currentFinancialYear?->id,
                    'billing_period_start' => $billingStart,
                    'billing_period_end' => $billingEnd,
                    'due_date' => $billingEnd->copy()->addDays(15),
                    'total_amount' => 0,
                    'tax_amount' => 0,
                    'late_fee' => 0,
                    'discount' => 0,
                    'net_amount' => 0,
                    'paid_amount' => 0,
                    'balance_due' => 0,
                    'status' => 'draft',
                ]);

                // Add charge head line items
                $applicableCharges = $chargeHeads->filter(function ($ch) use ($unit) {
                    return $ch->applies_to === 'all' || $ch->applies_to === $unit->unit_type;
                });

                foreach ($applicableCharges as $chargeHead) {
                    $amount = $chargeHead->calculateChargeForUnit($unit);

                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'charge_head_id' => $chargeHead->id,
                        'description' => $chargeHead->name,
                        'amount' => $amount,
                        'tax_rate' => 0,
                        'tax_amount' => 0,
                    ]);
                }

                // Recalculate totals
                $invoice->recalculate();
                $invoice->status = 'sent';
                $invoice->save();

                // Create journal entry for billing
                // Debit: Member Receivable
                // Credit: Each income account (from charge heads)
                $this->createBillingJournalEntry($invoice, $society->id);

                $invoices->push($invoice);
            }

            return $invoices;
        });
    }

    /**
     * Record a payment against an invoice.
     */
    public function recordPayment(Invoice $invoice, array $paymentData): Payment
    {
        return DB::transaction(function () use ($invoice, $paymentData) {
            $payment = Payment::create([
                'society_id' => $invoice->society_id,
                'invoice_id' => $invoice->id,
                'unit_id' => $invoice->unit_id,
                'member_id' => $invoice->member_id,
                'amount' => $paymentData['amount'],
                'payment_date' => $paymentData['payment_date'] ?? now(),
                'payment_method' => $paymentData['payment_method'],
                'transaction_reference' => $paymentData['transaction_reference'] ?? null,
                'cheque_no' => $paymentData['cheque_no'] ?? null,
                'bank_name' => $paymentData['bank_name'] ?? null,
                'receipt_number' => $this->generateReceiptNumber($invoice->society_id),
                'status' => 'confirmed',
                'notes' => $paymentData['notes'] ?? null,
            ]);

            // Update invoice
            $invoice->paid_amount += $payment->amount;
            $invoice->recalculate();
            $invoice->save();

            // Create journal entry for payment
            // Debit: Cash/Bank account
            // Credit: Member Receivable
            $this->createPaymentJournalEntry($payment, $invoice);

            return $payment;
        });
    }

    /**
     * Apply late fees to overdue invoices.
     */
    public function applyLateFees(Society $society): Collection
    {
        $lateFeeRules = $society->lateFeeRules ?? collect();
        $overdueInvoices = Invoice::withoutGlobalScopes()
            ->where('society_id', $society->id)
            ->overdue()
            ->where('late_fee', 0) // Not already applied
            ->get();

        $updated = collect();

        foreach ($overdueInvoices as $invoice) {
            $daysOverdue = $invoice->due_date->diffInDays(now());

            foreach ($lateFeeRules as $rule) {
                $fee = $rule->calculateFee((float) $invoice->net_amount, $daysOverdue);

                if ($fee > 0) {
                    $invoice->late_fee = $fee;
                    $invoice->recalculate();
                    $invoice->save();
                    $updated->push($invoice);
                    break; // Apply only one rule (the matching one)
                }
            }
        }

        return $updated;
    }

    /**
     * Get defaulter report — units with outstanding dues.
     */
    public function getDefaulterReport(int $societyId): Collection
    {
        return Invoice::withoutGlobalScopes()
            ->where('society_id', $societyId)
            ->where('balance_due', '>', 0)
            ->whereIn('status', ['sent', 'partial', 'overdue'])
            ->with(['unit.wing', 'member'])
            ->orderByDesc('balance_due')
            ->get()
            ->groupBy('unit_id')
            ->map(function ($invoices, $unitId) {
                $unit = $invoices->first()->unit;
                return [
                    'unit' => $unit ? $unit->display_name : "Unit #{$unitId}",
                    'wing' => $unit?->wing?->name,
                    'member' => $invoices->first()->member?->name,
                    'total_outstanding' => $invoices->sum('balance_due'),
                    'overdue_months' => $invoices->count(),
                    'oldest_due' => $invoices->min('due_date'),
                    'invoices' => $invoices->map(fn ($inv) => [
                        'invoice_number' => $inv->invoice_number,
                        'period' => $inv->billing_period_start?->format('M Y'),
                        'amount' => $inv->net_amount,
                        'paid' => $inv->paid_amount,
                        'balance' => $inv->balance_due,
                        'due_date' => $inv->due_date?->format('Y-m-d'),
                    ]),
                ];
            })
            ->values();
    }

    // ─── Private Helpers ───────────────────────────────────────────

    private function createBillingJournalEntry(Invoice $invoice, int $societyId): void
    {
        $lines = [];

        // Debit: Accounts Receivable (total amount)
        // We use a well-known system account code for receivables
        $receivableAccount = $this->getSystemAccount($societyId, 'accounts-receivable');
        $lines[] = [
            'account_id' => $receivableAccount->id,
            'debit' => (float) $invoice->net_amount,
            'credit' => 0,
            'narration' => "Invoice {$invoice->invoice_number} - {$invoice->unit->display_name ?? 'Unit'}",
        ];

        // Credit: Each charge head's corresponding income account
        foreach ($invoice->items as $item) {
            if ($item->chargeHead && $item->chargeHead->account_id) {
                $lines[] = [
                    'account_id' => $item->chargeHead->account_id,
                    'debit' => 0,
                    'credit' => (float) $item->amount,
                    'narration' => $item->description,
                ];
            }
        }

        // Only create if we have valid credit lines
        if (count($lines) >= 2) {
            $entry = $this->accountingService->createJournalEntry([
                'society_id' => $societyId,
                'date' => $invoice->billing_period_start->format('Y-m-d'),
                'narration' => "Monthly billing - Invoice {$invoice->invoice_number}",
                'entry_type' => 'billing',
                'reference_type' => Invoice::class,
                'reference_id' => $invoice->id,
                'lines' => $lines,
            ]);

            // Auto-post billing entries
            $this->accountingService->postEntry($entry);

            $invoice->update(['journal_entry_id' => $entry->id]);
        }
    }

    private function createPaymentJournalEntry(Payment $payment, Invoice $invoice): void
    {
        $societyId = $payment->society_id;

        // Determine debit account based on payment method
        $debitAccountCode = match ($payment->payment_method) {
            'cash' => 'cash-in-hand',
            default => 'bank-account',
        };

        $debitAccount = $this->getSystemAccount($societyId, $debitAccountCode);
        $receivableAccount = $this->getSystemAccount($societyId, 'accounts-receivable');

        $entry = $this->accountingService->createJournalEntry([
            'society_id' => $societyId,
            'date' => $payment->payment_date->format('Y-m-d'),
            'narration' => "Payment received - Receipt {$payment->receipt_number} for Invoice {$invoice->invoice_number}",
            'entry_type' => 'payment',
            'reference_type' => Payment::class,
            'reference_id' => $payment->id,
            'lines' => [
                [
                    'account_id' => $debitAccount->id,
                    'debit' => (float) $payment->amount,
                    'credit' => 0,
                    'narration' => "Payment via {$payment->payment_method}",
                ],
                [
                    'account_id' => $receivableAccount->id,
                    'debit' => 0,
                    'credit' => (float) $payment->amount,
                    'narration' => "Against Invoice {$invoice->invoice_number}",
                ],
            ],
        ]);

        $this->accountingService->postEntry($entry);
        $payment->update(['journal_entry_id' => $entry->id]);
    }

    private function getSystemAccount(int $societyId, string $code): \App\Domain\Accounting\Models\Account
    {
        $account = \App\Domain\Accounting\Models\Account::withoutGlobalScopes()
            ->where('society_id', $societyId)
            ->where('code', $code)
            ->where('is_system', true)
            ->first();

        if (!$account) {
            throw new \DomainException("System account '{$code}' not found. Run the chart of accounts seeder.");
        }

        return $account;
    }

    private function generateInvoiceNumber(int $societyId, Carbon $month): string
    {
        $prefix = 'INV-' . $month->format('Ym') . '-';
        $count = Invoice::withoutGlobalScopes()
            ->where('society_id', $societyId)
            ->where('invoice_number', 'like', $prefix . '%')
            ->count();

        return $prefix . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    private function generateReceiptNumber(int $societyId): string
    {
        $prefix = 'RCP-' . now()->format('Ym') . '-';
        $count = Payment::withoutGlobalScopes()
            ->where('society_id', $societyId)
            ->where('receipt_number', 'like', $prefix . '%')
            ->count();

        return $prefix . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }
}
