<?php

namespace App\Domain\Communication\Models;

use Illuminate\Database\Eloquent\Model;

class NoticeAttachment extends Model
{
    protected $fillable = ['notice_id', 'file_name', 'file_path', 'file_type'];
    public function notice() { return $this->belongsTo(Notice::class); }
}
