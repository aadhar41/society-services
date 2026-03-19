<?php

namespace App\Domain\Booking\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasUuid, HasSocietyScope;

    protected $fillable = [
        'society_id', 'facility_id', 'unit_id', 'member_id', 'booking_date',
        'slot_id', 'amount', 'payment_id', 'status', 'notes',
    ];

    protected $casts = ['booking_date' => 'date', 'amount' => 'decimal:2'];

    public function facility() { return $this->belongsTo(Facility::class); }
    public function unit()     { return $this->belongsTo(\App\Domain\Society\Models\Unit::class); }
    public function member()   { return $this->belongsTo(\App\Domain\Member\Models\Member::class); }
    public function slot()     { return $this->belongsTo(FacilitySlot::class, 'slot_id'); }
    public function payment()  { return $this->belongsTo(\App\Domain\Accounting\Models\Payment::class); }
}
