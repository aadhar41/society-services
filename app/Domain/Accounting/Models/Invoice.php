<?php

namespace App\Domain\Accounting\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use App\Domain\Shared\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasUuid, HasSocietyScope, Auditable;

    protected $fillable = [
        'society_id',
        'unit_id',
        'member_id',
        'invoice_number',
        'financial_year_id',
        'billing_period_start',
        'billing_period_end',
        'due_date',
        'total_amount',
        'tax_amount',
        'late_fee',
        'discount',
        'net_amount',
        'paid_amount',
        'balance_due',
        'status',
        'journal_entry_id',
        'notes',
    ];

    protected $casts = [
        'billing_period_start' => 'date',
        'billing_period_end' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_due' => 'decimal:2',
    ];

    // ─── Relationships ─────────────────────────────────────────────

    public function unit()
    {
        return $this->belongsTo(\App\Domain\Society\Models\Unit::class);
    }

    public function member()
    {
        return $this->belongsTo(\App\Domain\Member\Models\Member::class);
    }

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    // ─── Business Logic ────────────────────────────────────────────

    /**
     * Recalculate totals from line items.
     */
    public function recalculate(): self
    {
        $this->total_amount = $this->items()->sum('amount');
        $this->tax_amount = $this->items()->sum('tax_amount');
        $this->net_amount = $this->total_amount + $this->tax_amount + $this->late_fee - $this->discount;
        $this->balance_due = $this->net_amount - $this->paid_amount;

        // Update status based on payment
        if ($this->balance_due <= 0) {
            $this->status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
        } elseif ($this->due_date && $this->due_date->isPast()) {
            $this->status = 'overdue';
        }

        return $this;
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->balance_due > 0;
    }

    // ─── Scopes ────────────────────────────────────────────────────

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['sent', 'partial', 'overdue']);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())->where('balance_due', '>', 0);
    }

    public function scopeForPeriod($query, $start, $end)
    {
        return $query->whereBetween('billing_period_start', [$start, $end]);
    }
}
