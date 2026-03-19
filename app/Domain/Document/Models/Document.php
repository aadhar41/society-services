<?php

namespace App\Domain\Document\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasUuid, HasSocietyScope;

    protected $table = 'erp_documents';

    protected $fillable = [
        'society_id', 'title', 'category', 'file_path',
        'uploaded_by', 'meeting_date', 'is_public',
    ];

    protected $casts = ['meeting_date' => 'date', 'is_public' => 'boolean'];

    public function uploadedBy() { return $this->belongsTo(\App\Models\User::class, 'uploaded_by'); }
}
