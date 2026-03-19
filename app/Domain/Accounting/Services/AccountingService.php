<?php

namespace App\Domain\Accounting\Services;

use App\Domain\Accounting\Models\Account;
use App\Domain\Accounting\Models\FinancialYear;
use App\Domain\Accounting\Models\JournalEntry;
use App\Domain\Accounting\Models\JournalEntryLine;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * AccountingService — Core double-entry bookkeeping engine.
 *
 * ALL financial transactions must go through this service.
 * It enforces:
 *   1. Every entry must balance (total debits == total credits)
 *   2. Posted entries cannot be modified (only voided via reversal)
 *   3. Entries in locked financial years cannot be created
 *   4. Balances are never stored directly — always derived
 */
class AccountingService
{
    /**
     * Create a new journal entry with lines.
     *
     * @param array $data [
     *   'society_id' => int,
     *   'date' => string (Y-m-d),
     *   'narration' => string,
     *   'entry_type' => string (manual|billing|payment|receipt|auto),
     *   'reference_type' => ?string,
     *   'reference_id' => ?int,
     *   'lines' => [
     *     ['account_id' => int, 'debit' => float, 'credit' => float, 'narration' => ?string],
     *     ...
     *   ]
     * ]
     *
     * @throws \DomainException
     */
    public function createJournalEntry(array $data): JournalEntry
    {
        return DB::transaction(function () use ($data) {
            // Validate financial year
            $financialYear = $this->resolveFinancialYear($data['society_id'], $data['date']);

            if ($financialYear->is_locked) {
                throw new \DomainException("Financial year '{$financialYear->name}' is locked. Cannot create entries.");
            }

            // Validate balance before saving
            $this->validateLinesBalance($data['lines']);

            // Generate entry number
            $entryNumber = $this->generateEntryNumber($data['society_id'], $financialYear->id);

            // Create the journal entry
            $entry = JournalEntry::create([
                'society_id' => $data['society_id'],
                'financial_year_id' => $financialYear->id,
                'entry_number' => $entryNumber,
                'date' => $data['date'],
                'narration' => $data['narration'],
                'entry_type' => $data['entry_type'] ?? 'manual',
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'created_by' => auth()->id(),
                'is_posted' => false,
            ]);

            // Create the lines
            foreach ($data['lines'] as $line) {
                $entry->lines()->create([
                    'account_id' => $line['account_id'],
                    'debit' => $line['debit'] ?? 0,
                    'credit' => $line['credit'] ?? 0,
                    'narration' => $line['narration'] ?? null,
                ]);
            }

            return $entry->load('lines.account');
        });
    }

    /**
     * Post a journal entry (make it permanent).
     * Once posted, entries affect account balances and cannot be modified.
     *
     * @throws \DomainException
     */
    public function postEntry(JournalEntry $entry): JournalEntry
    {
        if ($entry->is_posted) {
            throw new \DomainException("Entry #{$entry->entry_number} is already posted.");
        }

        if ($entry->isVoided()) {
            throw new \DomainException("Cannot post a voided entry.");
        }

        // Final balance check
        $entry->validateBalance();

        $entry->update([
            'is_posted' => true,
            'approved_by' => auth()->id(),
        ]);

        return $entry->fresh();
    }

    /**
     * Void a posted journal entry by creating a reversal entry.
     * The original entry is marked as voided, and a mirror-image entry is created.
     *
     * @throws \DomainException
     */
    public function voidEntry(JournalEntry $entry, string $reason): JournalEntry
    {
        if (!$entry->is_posted) {
            throw new \DomainException("Only posted entries can be voided.");
        }

        if ($entry->isVoided()) {
            throw new \DomainException("Entry #{$entry->entry_number} is already voided.");
        }

        return DB::transaction(function () use ($entry, $reason) {
            // Mark original as voided
            $entry->update([
                'voided_at' => now(),
                'void_reason' => $reason,
            ]);

            // Create reversal entry (swap debits and credits)
            $reversalLines = $entry->lines->map(function ($line) {
                return [
                    'account_id' => $line->account_id,
                    'debit' => $line->credit,   // Swap
                    'credit' => $line->debit,   // Swap
                    'narration' => "Reversal: {$line->narration}",
                ];
            })->toArray();

            $reversal = $this->createJournalEntry([
                'society_id' => $entry->society_id,
                'date' => now()->format('Y-m-d'),
                'narration' => "Reversal of #{$entry->entry_number}: {$reason}",
                'entry_type' => $entry->entry_type,
                'reference_type' => get_class($entry),
                'reference_id' => $entry->id,
                'lines' => $reversalLines,
            ]);

            // Auto-post the reversal
            $this->postEntry($reversal);

            return $reversal;
        });
    }

    /**
     * Get the balance of an account as of a specific date.
     */
    public function getAccountBalance(Account $account, ?\DateTimeInterface $asOf = null): float
    {
        return $account->calculateBalance($asOf);
    }

    /**
     * Get the general ledger for an account (all transactions).
     */
    public function getLedger(Account $account, ?string $startDate = null, ?string $endDate = null): Collection
    {
        $query = JournalEntryLine::where('account_id', $account->id)
            ->whereHas('journalEntry', function ($q) use ($startDate, $endDate) {
                $q->where('is_posted', true)->whereNull('voided_at');

                if ($startDate) {
                    $q->where('date', '>=', $startDate);
                }
                if ($endDate) {
                    $q->where('date', '<=', $endDate);
                }
            })
            ->with('journalEntry')
            ->orderBy(
                JournalEntry::select('date')
                    ->whereColumn('journal_entries.id', 'journal_entry_lines.journal_entry_id')
                    ->limit(1)
            );

        return $query->get();
    }

    /**
     * Generate Trial Balance report.
     *
     * Returns a collection of accounts with their debit/credit totals.
     */
    public function getTrialBalance(int $societyId, ?string $asOfDate = null): Collection
    {
        $query = JournalEntryLine::query()
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.code',
                'account_groups.nature',
                DB::raw('COALESCE(SUM(journal_entry_lines.debit), 0) as total_debit'),
                DB::raw('COALESCE(SUM(journal_entry_lines.credit), 0) as total_credit')
            )
            ->join('accounts', 'journal_entry_lines.account_id', '=', 'accounts.id')
            ->join('account_groups', 'accounts.account_group_id', '=', 'account_groups.id')
            ->join('journal_entries', 'journal_entry_lines.journal_entry_id', '=', 'journal_entries.id')
            ->where('journal_entries.society_id', $societyId)
            ->where('journal_entries.is_posted', true)
            ->whereNull('journal_entries.voided_at');

        if ($asOfDate) {
            $query->where('journal_entries.date', '<=', $asOfDate);
        }

        return $query->groupBy('accounts.id', 'accounts.name', 'accounts.code', 'account_groups.nature')
            ->orderBy('accounts.code')
            ->get();
    }

    /**
     * Generate Balance Sheet (Assets = Liabilities + Equity).
     */
    public function getBalanceSheet(int $societyId, string $asOfDate): array
    {
        $trialBalance = $this->getTrialBalance($societyId, $asOfDate);

        $assets = $trialBalance->where('nature', 'asset');
        $liabilities = $trialBalance->where('nature', 'liability');
        $equity = $trialBalance->where('nature', 'equity');

        // Include net income (revenue - expenses) in equity
        $income = $trialBalance->where('nature', 'income');
        $expenses = $trialBalance->where('nature', 'expense');

        $totalIncome = $income->sum('total_credit') - $income->sum('total_debit');
        $totalExpenses = $expenses->sum('total_debit') - $expenses->sum('total_credit');
        $netIncome = $totalIncome - $totalExpenses;

        return [
            'as_of_date' => $asOfDate,
            'assets' => [
                'items' => $assets->map(fn($a) => [
                    'name' => $a->name,
                    'code' => $a->code,
                    'balance' => $a->total_debit - $a->total_credit,
                ]),
                'total' => $assets->sum('total_debit') - $assets->sum('total_credit'),
            ],
            'liabilities' => [
                'items' => $liabilities->map(fn($l) => [
                    'name' => $l->name,
                    'code' => $l->code,
                    'balance' => $l->total_credit - $l->total_debit,
                ]),
                'total' => $liabilities->sum('total_credit') - $liabilities->sum('total_debit'),
            ],
            'equity' => [
                'items' => $equity->map(fn($e) => [
                    'name' => $e->name,
                    'code' => $e->code,
                    'balance' => $e->total_credit - $e->total_debit,
                ]),
                'retained_earnings' => $netIncome,
                'total' => ($equity->sum('total_credit') - $equity->sum('total_debit')) + $netIncome,
            ],
        ];
    }

    /**
     * Generate Profit & Loss statement.
     */
    public function getProfitAndLoss(int $societyId, string $startDate, string $endDate): array
    {
        $trialBalance = DB::table('journal_entry_lines')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.code',
                'account_groups.nature',
                'account_groups.name as group_name',
                DB::raw('COALESCE(SUM(journal_entry_lines.debit), 0) as total_debit'),
                DB::raw('COALESCE(SUM(journal_entry_lines.credit), 0) as total_credit')
            )
            ->join('accounts', 'journal_entry_lines.account_id', '=', 'accounts.id')
            ->join('account_groups', 'accounts.account_group_id', '=', 'account_groups.id')
            ->join('journal_entries', 'journal_entry_lines.journal_entry_id', '=', 'journal_entries.id')
            ->where('journal_entries.society_id', $societyId)
            ->where('journal_entries.is_posted', true)
            ->whereNull('journal_entries.voided_at')
            ->whereBetween('journal_entries.date', [$startDate, $endDate])
            ->whereIn('account_groups.nature', ['income', 'expense'])
            ->groupBy('accounts.id', 'accounts.name', 'accounts.code', 'account_groups.nature', 'account_groups.name')
            ->orderBy('accounts.code')
            ->get();

        $income = $trialBalance->where('nature', 'income');
        $expenses = $trialBalance->where('nature', 'expense');

        $totalIncome = $income->sum('total_credit') - $income->sum('total_debit');
        $totalExpenses = $expenses->sum('total_debit') - $expenses->sum('total_credit');

        return [
            'period' => ['start' => $startDate, 'end' => $endDate],
            'income' => [
                'items' => $income->map(fn($i) => [
                    'name' => $i->name,
                    'code' => $i->code,
                    'group' => $i->group_name,
                    'amount' => $i->total_credit - $i->total_debit,
                ]),
                'total' => $totalIncome,
            ],
            'expenses' => [
                'items' => $expenses->map(fn($e) => [
                    'name' => $e->name,
                    'code' => $e->code,
                    'group' => $e->group_name,
                    'amount' => $e->total_debit - $e->total_credit,
                ]),
                'total' => $totalExpenses,
            ],
            'net_income' => $totalIncome - $totalExpenses,
        ];
    }

    // ─── Private Helpers ───────────────────────────────────────────

    /**
     * Validate that lines balance (total debit == total credit).
     *
     * @throws \DomainException
     */
    private function validateLinesBalance(array $lines): void
    {
        if (count($lines) < 2) {
            throw new \DomainException("A journal entry must have at least 2 lines.");
        }

        $totalDebit = array_sum(array_column($lines, 'debit'));
        $totalCredit = array_sum(array_column($lines, 'credit'));

        if (abs($totalDebit - $totalCredit) > 0.01) {
            throw new \DomainException(
                "Journal entry is unbalanced. Total Debit: {$totalDebit}, Total Credit: {$totalCredit}"
            );
        }
    }

    /**
     * Resolve the financial year for a given date.
     *
     * @throws \DomainException
     */
    private function resolveFinancialYear(int $societyId, string $date): FinancialYear
    {
        $financialYear = FinancialYear::withoutGlobalScopes()
            ->where('society_id', $societyId)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();

        if (!$financialYear) {
            throw new \DomainException("No financial year found for date {$date}. Please create one first.");
        }

        return $financialYear;
    }

    /**
     * Generate a sequential entry number for the financial year.
     */
    private function generateEntryNumber(int $societyId, int $financialYearId): string
    {
        $lastNumber = JournalEntry::withoutGlobalScopes()
            ->where('society_id', $societyId)
            ->where('financial_year_id', $financialYearId)
            ->max('entry_number');

        $nextSeq = $lastNumber ? (int) substr($lastNumber, -6) + 1 : 1;

        return 'JV-' . str_pad($nextSeq, 6, '0', STR_PAD_LEFT);
    }
}
