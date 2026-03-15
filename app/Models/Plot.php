<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\PlotModelTraits;
use Illuminate\Pipeline\Pipeline;
use Session;
use Auth;

class Plot extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PlotModelTraits;

    protected $table = 'plots';

    protected $fillable = ['unique_code', 'user_id', 'society_id', 'block_id', 'name', 'slug', 'total_floors', 'total_flats', 'plot_area', 'description', 'status', 'created_at', 'updated_at', 'deleted_at'];

    protected $guarded = ["user_id"];

    const EXCERPT_LENGTH = 250;
}
