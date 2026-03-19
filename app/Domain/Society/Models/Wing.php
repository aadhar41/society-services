<?php

namespace App\Domain\Society\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use App\Domain\Shared\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wing extends Model
{
    use HasFactory, HasUuid, HasSocietyScope, Auditable, SoftDeletes;

    protected $fillable = [
        'society_id',
        'name',
        'code',
        'total_floors',
        'description',
        'status',
    ];

    protected $casts = [
        'total_floors' => 'integer',
        'status' => 'boolean',
    ];

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function floors()
    {
        return $this->hasMany(Floor::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
