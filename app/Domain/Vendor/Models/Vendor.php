<?php

namespace App\Domain\Vendor\Models;

use App\Domain\Shared\Traits\HasUuid;
use App\Domain\Shared\Traits\HasSocietyScope;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasUuid, HasSocietyScope;

    protected $fillable = [
        'society_id', 'name', 'company', 'phone', 'email', 'gst_no',
        'pan_no', 'address', 'service_type', 'status',
    ];

    protected $casts = ['status' => 'boolean'];

    public function contracts() { return $this->hasMany(VendorContract::class); }
}
