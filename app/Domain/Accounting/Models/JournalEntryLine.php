<?php

namespace App\Domain\Accounting\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * JournalEntryLine — Individual debit/credit line in a journal entry.
 *
 * Rules:
 * - Each line has EITHER a debit OR a credit, never both
 * - Sum of all debits in an entry MUST equal sum of all credits
 */
class JournalEntryLine extends Model
{
    protected $fillable = [
        'journal_entry_id',
        'account_id',
        'debit',
        'credit',
        'narration',
    ];

    protected $casts = [
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
    ];

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the net effect (positive = debit, negative = credit).
     */
    public function getNetAmountAttribute(): float
    {
        return (float) $this->debit - (float) $this->credit;
    }
}
