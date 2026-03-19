<?php

namespace App\Domain\Member\Models;

use App\Domain\Shared\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class MemberVehicle extends Model
{
    use HasUuid;

    protected $fillable = [
        'member_id',
        'parking_slot_id',
        'vehicle_type',
        'registration_no',
        'make',
        'model',
        'color',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function parkingSlot()
    {
        return $this->belongsTo(\App\Domain\Society\Models\ParkingSlot::class);
    }
}
