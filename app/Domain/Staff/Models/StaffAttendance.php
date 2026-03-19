<?php

namespace App\Domain\Staff\Models;

use Illuminate\Database\Eloquent\Model;

class StaffAttendance extends Model
{
    protected $table = 'staff_attendance';

    protected $fillable = ['staff_id', 'date', 'check_in', 'check_out', 'status'];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function staff() { return $this->belongsTo(Staff::class); }
}
