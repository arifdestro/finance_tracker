<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DebtPayment extends Model
{
    protected $fillable = [
        'debt_id',
        'wallet_id',
        'amount',
        'payment_date',
        'notes'
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
        ];
    }

    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
