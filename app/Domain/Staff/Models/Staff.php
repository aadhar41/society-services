<?php

namespace App\Domain\Staff\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasUuid, HasSocietyScope;

    protected $table = 'staff';

    protected $fillable = [
        'society_id', 'user_id', 'name', 'phone', 'role',
        'department', 'salary', 'joining_date', 'status',
    ];

    protected $casts = ['salary' => 'decimal:2', 'joining_date' => 'date', 'status' => 'boolean'];

    public function user()       { return $this->belongsTo(\App\Models\User::class); }
    public function attendance() { return $this->hasMany(StaffAttendance::class); }
}
