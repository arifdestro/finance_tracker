<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\TransactionObserver;

#[ObservedBy(TransactionObserver::class)]
class Transaction extends Model
{
    protected $fillable = ['user_id', 'wallet_id', 'category_id', 'type', 'amount', 'description', 'transaction_date', 'to_wallet_id', 'notes'];

    protected function casts(): array {
        return [
            'transaction_date' => 'date',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function wallet() { return $this->belongsTo(Wallet::class, 'wallet_id'); }
    public function toWallet() { return $this->belongsTo(Wallet::class, 'to_wallet_id'); }
    public function category() { return $this->belongsTo(Category::class); }
}
