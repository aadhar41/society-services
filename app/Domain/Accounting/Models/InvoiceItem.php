<?php

namespace App\Domain\Accounting\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'charge_head_id',
        'description',
        'amount',
        'tax_rate',
        'tax_amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function chargeHead()
    {
        return $this->belongsTo(ChargeHead::class);
    }
}
