<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payable extends Model
{
    protected $fillable = [
        'user_id', 'wallet_id', 'lender_name', 'amount', 'paid_amount',
        'description', 'payable_date', 'due_date', 'is_paid', 'paid_date', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'payable_date' => 'date',
            'due_date' => 'date',
            'paid_date' => 'date',
            'is_paid' => 'boolean',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function wallet() { return $this->belongsTo(Wallet::class); }
    public function payments() { return $this->hasMany(PayablePayment::class)->orderBy('payment_date', 'desc')->orderBy('created_at', 'desc'); }

    public function getRemainingAmountAttribute()
    {
        return max(0, $this->amount - $this->paid_amount);
    }
}
