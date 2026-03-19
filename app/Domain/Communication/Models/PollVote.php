<?php

namespace App\Domain\Communication\Models;

use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
{
    protected $fillable = ['poll_id', 'user_id', 'option_index', 'voted_at'];
    protected $casts = ['option_index' => 'integer', 'voted_at' => 'datetime'];

    public function poll() { return $this->belongsTo(Poll::class); }
    public function user() { return $this->belongsTo(\App\Models\User::class); }
}
