<?php

namespace App\Domain\Accounting\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use Illuminate\Database\Eloquent\Model;

/**
 * Account — Individual General Ledger account.
 *
 * Every financial transaction ultimately debits/credits one or more accounts.
 * Balances are NEVER stored directly — they are always derived from journal entry lines.
 */
class Account extends Model
{
    use HasUuid, HasSocietyScope;

    protected $fillable = [
        'society_id',
        'account_group_id',
        'name',
        'code',
        'description',
        'is_system',
        'is_active',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
    ];

    // ─── Relationships ─────────────────────────────────────────────

    public function accountGroup()
    {
        return $this->belongsTo(AccountGroup::class);
    }

    public function journalEntryLines()
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    public function openingBalances()
    {
        return $this->hasMany(OpeningBalance::class);
    }

    // ─── Balance Calculations ──────────────────────────────────────

    /**
     * Get the current balance of this account.
     * For Assets & Expenses: balance = sum(debit) - sum(credit)
     * For Liabilities, Income & Equity: balance = sum(credit) - sum(debit)
     */
    public function getBalanceAttribute(): float
    {
        return $this->calculateBalance();
    }

    /**
     * Calculate balance as of a specific date.
     */
    public function calculateBalance(?\DateTimeInterface $asOf = null): float
    {
        $query = $this->journalEntryLines()
            ->whereHas('journalEntry', function ($q) use ($asOf) {
                $q->where('is_posted', true);
                if ($asOf) {
                    $q->where('date', '<=', $asOf);
                }
            });

        $totals = $query->selectRaw('COALESCE(SUM(debit), 0) as total_debit, COALESCE(SUM(credit), 0) as total_credit')
            ->first();

        $nature = $this->accountGroup->nature ?? 'asset';

        // Assets & Expenses have debit-normal balance
        if (in_array($nature, ['asset', 'expense'])) {
            return (float) ($totals->total_debit - $totals->total_credit);
        }

        // Liabilities, Income & Equity have credit-normal balance
        return (float) ($totals->total_credit - $totals->total_debit);
    }

    // ─── Scopes ────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByNature($query, string $nature)
    {
        return $query->whereHas('accountGroup', function ($q) use ($nature) {
            $q->where('nature', $nature);
        });
    }
}
