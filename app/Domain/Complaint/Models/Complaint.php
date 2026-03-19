<?php

namespace App\Domain\Complaint\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use App\Domain\Shared\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasUuid, HasSocietyScope, Auditable;

    protected $fillable = [
        'society_id', 'unit_id', 'member_id', 'category_id', 'ticket_number',
        'title', 'description', 'priority', 'status', 'assigned_to',
        'resolved_at', 'closed_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function unit()     { return $this->belongsTo(\App\Domain\Society\Models\Unit::class); }
    public function member()   { return $this->belongsTo(\App\Domain\Member\Models\Member::class); }
    public function category() { return $this->belongsTo(ComplaintCategory::class, 'category_id'); }
    public function assignee() { return $this->belongsTo(\App\Models\User::class, 'assigned_to'); }
    public function comments() { return $this->hasMany(ComplaintComment::class); }

    public function scopeOpen($query)     { return $query->whereIn('status', ['open', 'in_progress']); }
    public function scopeResolved($query) { return $query->whereIn('status', ['resolved', 'closed']); }
}
