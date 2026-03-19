<?php

namespace App\Domain\Complaint\Models;

use App\Domain\Shared\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class ComplaintComment extends Model
{
    use HasUuid;

    protected $fillable = ['complaint_id', 'user_id', 'comment', 'attachments', 'is_internal'];

    protected $casts = ['attachments' => 'array', 'is_internal' => 'boolean'];

    public function complaint() { return $this->belongsTo(Complaint::class); }
    public function user()      { return $this->belongsTo(\App\Models\User::class); }
}
