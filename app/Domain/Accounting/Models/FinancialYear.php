<?php

namespace App\Domain\Accounting\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use Illuminate\Database\Eloquent\Model;

class FinancialYear extends Model
{
    use HasUuid, HasSocietyScope;

    protected $fillable = [
        'society_id',
        'name',
        'start_date',
        'end_date',
        'is_current',
        'is_locked',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'is_locked' => 'boolean',
    ];

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function openingBalances()
    {
        return $this->hasMany(OpeningBalance::class);
    }

    /**
     * Check if a given date falls within this financial year.
     */
    public function containsDate(\DateTimeInterface $date): bool
    {
        return $date >= $this->start_date && $date <= $this->end_date;
    }

    /**
     * Scope to get the current active financial year.
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }
}
