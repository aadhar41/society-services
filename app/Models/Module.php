<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'erp_modules';
    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    public function roles()
    {
        return $this->belongsToMany(\App\Models\SystemRole::class, 'erp_role_modules', 'module_id', 'role_id')
            ->withPivot('is_enabled')
            ->withTimestamps();
    }

    public function societies()
    {
        return $this->belongsToMany(\App\Domain\Society\Models\Society::class, 'erp_society_modules', 'module_id', 'society_id')
            ->withPivot('is_enabled')
            ->withTimestamps();
    }
}
