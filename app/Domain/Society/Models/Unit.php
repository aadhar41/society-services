<?php

namespace App\Domain\Society\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use App\Domain\Shared\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, HasUuid, HasSocietyScope, Auditable, SoftDeletes;

    protected $fillable = [
        'society_id',
        'wing_id',
        'floor_id',
        'unit_number',
        'unit_type',
        'area_sqft',
        'parking_count',
        'intercom_no',
        'status',
    ];

    protected $casts = [
        'area_sqft' => 'decimal:2',
        'parking_count' => 'integer',
        'status' => 'boolean',
    ];

    // ─── Relationships ─────────────────────────────────────────────

    public function wing()
    {
        return $this->belongsTo(Wing::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function parkingSlots()
    {
        return $this->hasMany(ParkingSlot::class);
    }

    public function members()
    {
        return $this->hasMany(\App\Domain\Member\Models\Member::class);
    }

    public function currentOwner()
    {
        return $this->hasOne(\App\Domain\Member\Models\Member::class)
            ->where('member_type', 'owner')
            ->where('is_primary', true)
            ->whereNull('move_out_date');
    }

    public function currentTenant()
    {
        return $this->hasOne(\App\Domain\Member\Models\Member::class)
            ->where('member_type', 'tenant')
            ->where('is_primary', true)
            ->whereNull('move_out_date');
    }

    public function invoices()
    {
        return $this->hasMany(\App\Domain\Accounting\Models\Invoice::class);
    }

    public function complaints()
    {
        return $this->hasMany(\App\Domain\Complaint\Models\Complaint::class);
    }

    /**
     * Get the display label for this unit (e.g., "A-101").
     */
    public function getDisplayNameAttribute(): string
    {
        $wingCode = $this->wing?->code ?? $this->wing?->name ?? '';
        return trim("{$wingCode}-{$this->unit_number}", '-');
    }
}
