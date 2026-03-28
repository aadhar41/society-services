<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'max_societies',
        'description',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
