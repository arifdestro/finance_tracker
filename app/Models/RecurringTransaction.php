<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringTransaction extends Model
{
    protected $fillable = ['user_id', 'wallet_id', 'category_id', 'type', 'amount', 'description', 'to_wallet_id', 'frequency', 'next_date', 'is_active'];

    protected function casts(): array {
        return [
            'next_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function wallet() { return $this->belongsTo(Wallet::class, 'wallet_id'); }
    public function toWallet() { return $this->belongsTo(Wallet::class, 'to_wallet_id'); }
    public function category() { return $this->belongsTo(Category::class); }
}
