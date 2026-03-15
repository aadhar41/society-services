<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    
    protected $table = 'expenses';

    protected $fillable = ['unique_code', 'user_id', 'society_id', 'block_id', 'plot_id', 'flat_id', 'type', 'category', 'date', 'year', 'month', 'amount' , 'payment_mode', 'transaction_id', 'description', 'attachments', 'payment_status', 'status', 'created_at', 'updated_at', 'deleted_at'];
}
