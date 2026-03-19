<?php

namespace App\Domain\Accounting\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use App\Domain\Shared\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class ChargeHead extends Model
{
    use HasUuid, HasSocietyScope, Auditable;

    protected $fillable = [
        'society_id',
        'name',
        'account_id',
        'amount',
        'frequency',
        'is_area_based',
        'rate_per_sqft',
        'applies_to',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'rate_per_sqft' => 'decimal:2',
        'is_area_based' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Calculate the actual charge for a given unit.
     */
    public function calculateChargeForUnit(\App\Domain\Society\Models\Unit $unit): float
    {
        if ($this->is_area_based && $this->rate_per_sqft > 0) {
            return (float) ($this->rate_per_sqft * $unit->area_sqft);
        }

        return (float) $this->amount;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForUnitType($query, string $unitType)
    {
        return $query->where(function ($q) use ($unitType) {
            $q->where('applies_to', 'all')
              ->orWhere('applies_to', $unitType);
        });
    }
}
