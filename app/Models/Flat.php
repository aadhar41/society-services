<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\FlatModelTraits;
use Illuminate\Pipeline\Pipeline;
use Session;
use Auth;

class Flat extends Model
{
    use HasFactory;
    use SoftDeletes;
    use FlatModelTraits;

    protected $table = 'flats';

    protected $fillable = ['unique_code', 'user_id', 'society_id', 'block_id', 'plot_id', 'name', 'slug', 'flat_no', 'description', 'mobile_no','property_type','tenant_name','tenant_contact', 'new_road_outstanding', 'status', 'created_at', 'updated_at', 'deleted_at'];

    protected $guarded = ["user_id"];

    const EXCERPT_LENGTH = 250;
}
