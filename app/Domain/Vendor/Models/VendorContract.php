<?php

namespace App\Domain\Vendor\Models;

use App\Domain\Shared\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class VendorContract extends Model
{
    use HasUuid;

    protected $fillable = [
        'vendor_id', 'title', 'start_date', 'end_date', 'amount',
        'payment_terms', 'document_path', 'status',
    ];

    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'amount' => 'decimal:2'];

    public function vendor() { return $this->belongsTo(Vendor::class); }

    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->end_date && $this->end_date->diffInDays(now()) <= $days;
    }
}
