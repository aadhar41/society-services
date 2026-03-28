<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemRole extends Model
{
    protected $table = 'erp_roles';
    protected $fillable = ['name', 'slug'];

    public function modules()
    {
        return $this->belongsToMany(\App\Models\Module::class, 'erp_role_modules', 'role_id', 'module_id')
            ->withPivot('is_enabled')
            ->withTimestamps();
    }
}
