<?php

namespace App\Domain\Accounting\Models;

use App\Domain\Shared\Traits\HasSocietyScope;
use Illuminate\Database\Eloquent\Model;

class LateFeeRule extends Model
{
    use HasSocietyScope;

    protected $fillable = [
        'society_id',
        'days_after_due',
        'fee_type',
        'fee_value',
        'max_fee',
        'is_compounding',
    ];

    protected $casts = [
        'days_after_due' => 'integer',
        'fee_value' => 'decimal:2',
        'max_fee' => 'decimal:2',
        'is_compounding' => 'boolean',
    ];

    /**
     * Calculate the late fee for a given invoice amount and days overdue.
     */
    public function calculateFee(float $invoiceAmount, int $daysOverdue): float
    {
        if ($daysOverdue < $this->days_after_due) {
            return 0.0;
        }

        $fee = $this->fee_type === 'percentage'
            ? ($invoiceAmount * $this->fee_value / 100)
            : (float) $this->fee_value;

        if ($this->max_fee > 0) {
            $fee = min($fee, (float) $this->max_fee);
        }

        return $fee;
    }
}
