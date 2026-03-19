<?php

namespace App\Domain\Communication\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = ['society_id', 'user_id', 'channel', 'subject', 'body', 'status', 'sent_at'];
    protected $casts = ['sent_at' => 'datetime'];

    public function user() { return $this->belongsTo(\App\Models\User::class); }
}
