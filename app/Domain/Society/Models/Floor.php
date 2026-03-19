<?php

namespace App\Domain\Society\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory, HasUuid, HasSocietyScope;

    protected $fillable = [
        'society_id',
        'wing_id',
        'floor_number',
        'name',
        'status',
    ];

    protected $casts = [
        'floor_number' => 'integer',
        'status' => 'boolean',
    ];

    public function wing()
    {
        return $this->belongsTo(Wing::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
