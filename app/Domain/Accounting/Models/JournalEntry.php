<?php

namespace App\Domain\Accounting\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use App\Domain\Shared\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

/**
 * JournalEntry — Master journal entry record.
 *
 * Every financial transaction in the system creates a journal entry.
 * A journal entry MUST have 2+ lines where sum(debits) == sum(credits).
 *
 * Entry types:
 *   - manual: Manually created by accountant
 *   - billing: Auto-created when invoices are generated
 *   - payment: Auto-created when payments are recorded
 *   - receipt: Auto-created for receipt vouchers
 *   - auto: System-generated (late fees, etc.)
 *
 * Lifecycle: Draft → Posted. Posted entries cannot be modified (only voided via reversal).
 */
class JournalEntry extends Model
{
    use HasUuid, HasSocietyScope, Auditable;

    protected $fillable = [
        'society_id',
        'financial_year_id',
        'entry_number',
        'date',
        'narration',
        'reference_type',
        'reference_id',
        'entry_type',
        'created_by',
        'approved_by',
        'is_posted',
        'voided_at',
        'void_reason',
    ];

    protected $casts = [
        'date' => 'date',
        'is_posted' => 'boolean',
        'voided_at' => 'datetime',
    ];

    // ─── Relationships ─────────────────────────────────────────────

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class);
    }

    public function lines()
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function approvedByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    /**
     * Polymorphic reference (invoice, payment, etc.)
     */
    public function reference()
    {
        return $this->morphTo('reference');
    }

    // ─── Business Logic ────────────────────────────────────────────

    /**
     * Validate that debits equal credits.
     *
     * @throws \DomainException
     */
    public function validateBalance(): bool
    {
        $totals = $this->lines()
            ->selectRaw('COALESCE(SUM(debit), 0) as total_debit, COALESCE(SUM(credit), 0) as total_credit')
            ->first();

        $diff = abs($totals->total_debit - $totals->total_credit);

        if ($diff > 0.01) { // Allow 1 paisa rounding tolerance
            throw new \DomainException(
                "Journal entry #{$this->entry_number} is unbalanced. " .
                "Debits: {$totals->total_debit}, Credits: {$totals->total_credit}"
            );
        }

        return true;
    }

    /**
     * Get total debit amount.
     */
    public function getTotalDebitAttribute(): float
    {
        return (float) $this->lines()->sum('debit');
    }

    /**
     * Get total credit amount.
     */
    public function getTotalCreditAttribute(): float
    {
        return (float) $this->lines()->sum('credit');
    }

    /**
     * Check if this entry can be modified.
     */
    public function isEditable(): bool
    {
        return !$this->is_posted && is_null($this->voided_at);
    }

    /**
     * Check if this entry has been voided.
     */
    public function isVoided(): bool
    {
        return !is_null($this->voided_at);
    }

    // ─── Scopes ────────────────────────────────────────────────────

    public function scopePosted($query)
    {
        return $query->where('is_posted', true)->whereNull('voided_at');
    }

    public function scopeDraft($query)
    {
        return $query->where('is_posted', false)->whereNull('voided_at');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('entry_type', $type);
    }
}
