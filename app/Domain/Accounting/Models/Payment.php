<?php

namespace App\Domain\Accounting\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use App\Domain\Shared\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasUuid, HasSocietyScope, Auditable;

    protected $fillable = [
        'society_id',
        'invoice_id',
        'unit_id',
        'member_id',
        'amount',
        'payment_date',
        'payment_method',
        'transaction_reference',
        'cheque_no',
        'bank_name',
        'receipt_number',
        'journal_entry_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function unit()
    {
        return $this->belongsTo(\App\Domain\Society\Models\Unit::class);
    }

    public function member()
    {
        return $this->belongsTo(\App\Domain\Member\Models\Member::class);
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }
}
