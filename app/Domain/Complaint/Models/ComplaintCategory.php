<?php

namespace App\Domain\Complaint\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use Illuminate\Database\Eloquent\Model;

class ComplaintCategory extends Model
{
    use HasUuid, HasSocietyScope;

    protected $fillable = ['society_id', 'name', 'description', 'sla_hours'];

    protected $casts = ['sla_hours' => 'integer'];
}
