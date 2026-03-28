<?php

namespace App\Domain\Society\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Society extends Model
{
    use HasFactory, HasUuid, Auditable, SoftDeletes;

    protected $table = 'erp_societies';

    protected $fillable = [
        'name',
        'registration_no',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'country',
        'pincode',
        'logo',
        'phone',
        'email',
        'website',
        'financial_year_start',
        'settings',
        'status',
    ];

    protected $casts = [
        'settings' => 'array',
        'financial_year_start' => 'integer',
        'status' => 'boolean',
    ];

    // ─── Relationships ─────────────────────────────────────────────

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'society_user')
            ->withPivot('role_id', 'joined_at', 'status')
            ->withTimestamps();
    }

    public function wings()
    {
        return $this->hasMany(Wing::class);
    }

    public function floors()
    {
        return $this->hasMany(Floor::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function parkingSlots()
    {
        return $this->hasMany(ParkingSlot::class);
    }

    public function members()
    {
        return $this->hasMany(\App\Domain\Member\Models\Member::class);
    }

    public function accounts()
    {
        return $this->hasMany(\App\Domain\Accounting\Models\Account::class);
    }

    public function accountGroups()
    {
        return $this->hasMany(\App\Domain\Accounting\Models\AccountGroup::class);
    }

    public function financialYears()
    {
        return $this->hasMany(\App\Domain\Accounting\Models\FinancialYear::class);
    }

    public function currentFinancialYear()
    {
        return $this->hasOne(\App\Domain\Accounting\Models\FinancialYear::class)
            ->where('is_current', true);
    }

    public function invoices()
    {
        return $this->hasMany(\App\Domain\Accounting\Models\Invoice::class);
    }

    public function chargeHeads()
    {
        return $this->hasMany(\App\Domain\Accounting\Models\ChargeHead::class);
    }

    public function complaints()
    {
        return $this->hasMany(\App\Domain\Complaint\Models\Complaint::class);
    }

    public function visitors()
    {
        return $this->hasMany(\App\Domain\Visitor\Models\Visitor::class);
    }

    public function facilities()
    {
        return $this->hasMany(\App\Domain\Booking\Models\Facility::class);
    }

    public function notices()
    {
        return $this->hasMany(\App\Domain\Communication\Models\Notice::class);
    }

    public function staff()
    {
        return $this->hasMany(\App\Domain\Staff\Models\Staff::class);
    }

    public function vendors()
    {
        return $this->hasMany(\App\Domain\Vendor\Models\Vendor::class);
    }

    public function documents()
    {
        return $this->hasMany(\App\Domain\Document\Models\Document::class);
    }

    public function modules()
    {
        return $this->belongsToMany(\App\Models\Module::class, 'erp_society_modules', 'society_id', 'module_id')
            ->withPivot('is_enabled')
            ->withTimestamps();
    }
}
