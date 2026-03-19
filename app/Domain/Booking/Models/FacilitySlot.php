<?php

namespace App\Domain\Booking\Models;

use Illuminate\Database\Eloquent\Model;

class FacilitySlot extends Model
{
    protected $fillable = ['facility_id', 'day_of_week', 'start_time', 'end_time', 'is_available'];
    protected $casts = ['is_available' => 'boolean'];

    public function facility() { return $this->belongsTo(Facility::class); }
}
