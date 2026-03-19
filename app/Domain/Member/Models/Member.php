<?php

namespace App\Domain\Member\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use App\Domain\Shared\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, HasUuid, HasSocietyScope, Auditable, SoftDeletes;

    protected $fillable = [
        'society_id',
        'unit_id',
        'user_id',
        'member_type',
        'name',
        'phone',
        'email',
        'aadhar_no',
        'pan_no',
        'occupation',
        'move_in_date',
        'move_out_date',
        'is_primary',
        'status',
    ];

    protected $casts = [
        'move_in_date' => 'date',
        'move_out_date' => 'date',
        'is_primary' => 'boolean',
        'status' => 'boolean',
    ];

    // ─── Relationships ─────────────────────────────────────────────

    public function unit()
    {
        return $this->belongsTo(\App\Domain\Society\Models\Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function documents()
    {
        return $this->hasMany(MemberDocument::class);
    }

    public function vehicles()
    {
        return $this->hasMany(MemberVehicle::class);
    }

    public function invoices()
    {
        return $this->hasMany(\App\Domain\Accounting\Models\Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Domain\Accounting\Models\Payment::class);
    }

    // ─── Scopes ────────────────────────────────────────────────────

    public function scopeOwners($query)
    {
        return $query->where('member_type', 'owner');
    }

    public function scopeTenants($query)
    {
        return $query->where('member_type', 'tenant');
    }

    public function scopeActive($query)
    {
        return $query->whereNull('move_out_date')->where('status', true);
    }

    /**
     * Check if member has moved out.
     */
    public function hasMovedOut(): bool
    {
        return !is_null($this->move_out_date);
    }
}
