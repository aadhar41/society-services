<?php

namespace App\Domain\Accounting\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use Illuminate\Database\Eloquent\Model;

/**
 * AccountGroup — Chart of Accounts hierarchy.
 *
 * Nature types: asset, liability, income, expense, equity
 * System groups are auto-created and cannot be deleted.
 */
class AccountGroup extends Model
{
    use HasUuid, HasSocietyScope;

    protected $fillable = [
        'society_id',
        'name',
        'code',
        'parent_id',
        'nature',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Get all descendant groups recursively.
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }
}
