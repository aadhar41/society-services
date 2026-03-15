<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\SocietyModelTraits;
use Illuminate\Pipeline\Pipeline;
use Session;
use Auth;

class Society extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SocietyModelTraits;

    protected $table = 'societies';

    protected $fillable = ['unique_code', 'user_id', 'name', 'slug', 'registration_no', 'contact_email', 'office_contact', 'address', 'contact', 'description', 'country', 'state', 'city', 'postcode', 'map_link', 'status', 'created_at', 'updated_at', 'deleted_at'];

    protected $guarded = ["user_id"];

    const EXCERPT_LENGTH = 250;
}
