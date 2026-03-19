<?php

namespace App\Domain\Communication\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasUuid, HasSocietyScope;

    protected $fillable = [
        'society_id', 'title', 'description', 'options', 'start_date',
        'end_date', 'is_anonymous', 'created_by',
    ];

    protected $casts = [
        'options' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_anonymous' => 'boolean',
    ];

    public function votes()     { return $this->hasMany(PollVote::class); }
    public function createdBy() { return $this->belongsTo(\App\Models\User::class, 'created_by'); }

    public function isActive(): bool
    {
        return now()->between($this->start_date, $this->end_date);
    }
}
