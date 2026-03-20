<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    
    protected $table = 'expenses';

    protected $fillable = ['unique_code', 'user_id', 'society_id', 'block_id', 'plot_id', 'flat_id', 'type', 'category', 'date', 'year', 'month', 'amount' , 'payment_mode', 'transaction_id', 'description', 'attachments', 'payment_status', 'status', 'created_at', 'updated_at', 'deleted_at'];

    public function setDateAttribute($value)
    {
        if ($value instanceof \DateTime || $value instanceof \Carbon\Carbon) {
            $this->attributes['date'] = $value->format('Y-m-d 00:00:00');
        } else {
            $data = explode("/", $value);
            if (count($data) == 3) {
                $date = $data[2] . "-" . $data[1] . "-" . $data[0] . " 00:00:00";
                $this->attributes['date'] = $date;
            } else {
                $this->attributes['date'] = \Carbon\Carbon::parse($value)->format('Y-m-d 00:00:00');
            }
        }
    }
}
