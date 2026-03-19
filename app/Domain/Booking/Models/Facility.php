<?php

namespace App\Domain\Booking\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasUuid, HasSocietyScope;

    protected $fillable = [
        'society_id', 'name', 'description', 'capacity', 'booking_fee',
        'advance_required', 'rules', 'images', 'is_active',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'booking_fee' => 'decimal:2',
        'advance_required' => 'decimal:2',
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    public function slots()    { return $this->hasMany(FacilitySlot::class); }
    public function bookings() { return $this->hasMany(Booking::class); }
}
