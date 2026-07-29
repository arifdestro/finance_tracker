<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\Wallet;

class TransactionObserver
{
    public function created(Transaction $transaction): void
    {
        $this->applyBalance($transaction->type, $transaction->amount, $transaction->wallet_id, $transaction->to_wallet_id, 1);
    }

    public function updated(Transaction $transaction): void
    {
        $oldType = $transaction->getOriginal('type');
        $oldAmount = $transaction->getOriginal('amount');
        $oldWalletId = $transaction->getOriginal('wallet_id');
        $oldToWalletId = $transaction->getOriginal('to_wallet_id');

        // Revert old changes
        $this->applyBalance($oldType, $oldAmount, $oldWalletId, $oldToWalletId, -1);
        // Apply new changes
        $this->applyBalance($transaction->type, $transaction->amount, $transaction->wallet_id, $transaction->to_wallet_id, 1);
    }

    public function deleted(Transaction $transaction): void
    {
        $this->applyBalance($transaction->type, $transaction->amount, $transaction->wallet_id, $transaction->to_wallet_id, -1);
    }

    private function applyBalance($type, $amount, $walletId, $toWalletId, $multiplier)
    {
        $wallet = Wallet::find($walletId);
        if ($wallet) {
            if ($type === 'income') {
                $wallet->balance += ($amount * $multiplier);
            } elseif ($type === 'expense') {
                $wallet->balance -= ($amount * $multiplier);
            } elseif ($type === 'transfer') {
                $wallet->balance -= ($amount * $multiplier);
                
                $toWallet = Wallet::find($toWalletId);
                if ($toWallet) {
                    $toWallet->balance += ($amount * $multiplier);
                    $toWallet->save();
                }
            }
            $wallet->save();
        }
    }
}
