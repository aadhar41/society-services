<?php

namespace App\Domain\Society\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSlot extends Model
{
    use HasFactory, HasUuid, HasSocietyScope;

    protected $fillable = [
        'society_id',
        'unit_id',
        'slot_number',
        'slot_type',
        'location',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function vehicle()
    {
        return $this->hasOne(\App\Domain\Member\Models\MemberVehicle::class);
    }
}
