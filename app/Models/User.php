<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'is_superadmin',
        'license_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'is_superadmin' => 'boolean',
    ];

    /**
     * Societies this user belongs to (ERP multi-tenancy).
     */
    public function societies()
    {
        return $this->belongsToMany(\App\Domain\Society\Models\Society::class, 'society_user')
            ->withPivot('role_id', 'joined_at', 'status')
            ->withTimestamps();
    }

    /**
     * License associated with this user.
     */
    public function license()
    {
        return $this->belongsTo(License::class);
    }

    /**
     * Check if user can create more societies based on license.
     */
    public function canCreateMoreSocieties(): bool
    {
        if ($this->is_superadmin) {
            return true;
        }

        if (!$this->license) {
            return false;
        }

        return $this->societies()->count() < $this->license->max_societies;
    }
}
