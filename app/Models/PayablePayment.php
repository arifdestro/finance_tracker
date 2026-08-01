<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayablePayment extends Model
{
    protected $fillable = [
        'payable_id', 'wallet_id', 'amount', 'payment_date', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
        ];
    }

    public function payable() { return $this->belongsTo(Payable::class); }
    public function wallet() { return $this->belongsTo(Wallet::class); }
}
