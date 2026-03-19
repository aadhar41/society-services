<?php

namespace App\Domain\Communication\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasUuid, HasSocietyScope;

    protected $fillable = [
        'society_id', 'title', 'body', 'category', 'priority',
        'target_audience', 'published_at', 'expires_at', 'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function createdBy()   { return $this->belongsTo(\App\Models\User::class, 'created_by'); }
    public function attachments() { return $this->hasMany(NoticeAttachment::class); }

    public function scopePublished($query) { return $query->whereNotNull('published_at')->where('published_at', '<=', now()); }
    public function scopeActive($query)    { return $query->published()->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now())); }
}
