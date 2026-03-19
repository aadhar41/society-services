<?php

namespace App\Domain\Vendor\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasUuid, HasSocietyScope;

    protected $fillable = [
        'society_id', 'name', 'category', 'location', 'purchase_date',
        'purchase_price', 'current_value', 'condition', 'warranty_expires_at',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
        'current_value' => 'decimal:2',
        'warranty_expires_at' => 'date',
    ];
}
