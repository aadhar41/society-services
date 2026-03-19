<?php

namespace App\Domain\Member\Models;

use App\Domain\Shared\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class MemberDocument extends Model
{
    use HasUuid;

    protected $fillable = [
        'member_id',
        'document_type',
        'file_path',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function verifiedByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'verified_by');
    }

    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }
}
