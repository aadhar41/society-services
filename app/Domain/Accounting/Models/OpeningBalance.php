<?php

namespace App\Domain\Accounting\Models;

use App\Domain\Shared\Traits\HasSocietyScope;
use Illuminate\Database\Eloquent\Model;

class OpeningBalance extends Model
{
    use HasSocietyScope;

    protected $fillable = [
        'society_id',
        'account_id',
        'financial_year_id',
        'debit',
        'credit',
    ];

    protected $casts = [
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class);
    }
}
