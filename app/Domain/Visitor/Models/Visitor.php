<?php

namespace App\Domain\Visitor\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasUuid, HasSocietyScope;

    protected $fillable = [
        'society_id', 'unit_id', 'name', 'phone', 'vehicle_no', 'purpose',
        'visitor_type', 'photo', 'otp', 'otp_expires_at', 'approved_by',
        'check_in_at', 'check_out_at', 'status',
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
    ];

    protected $hidden = ['otp'];

    public function unit()       { return $this->belongsTo(\App\Domain\Society\Models\Unit::class); }
    public function approvedBy() { return $this->belongsTo(\App\Models\User::class, 'approved_by'); }

    public function scopeToday($query)     { return $query->whereDate('check_in_at', today()); }
    public function scopeCheckedIn($query) { return $query->whereNotNull('check_in_at')->whereNull('check_out_at'); }
}
