<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    protected $fillable = [
        'user_id', 'wallet_id', 'borrower_name', 'amount',
        'description', 'debt_date', 'due_date', 'is_paid', 'paid_date', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'debt_date' => 'date',
            'due_date' => 'date',
            'paid_date' => 'date',
            'is_paid' => 'boolean',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function wallet() { return $this->belongsTo(Wallet::class); }
}
