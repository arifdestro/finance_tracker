<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionList extends Component
{
    use WithPagination;

    public $filterType = '';
    public $filterWallet = '';
    public $filterCategory = '';

    public function render()
    {
        $query = Transaction::with(['wallet', 'category', 'toWallet'])
            ->where('user_id', Auth::id())
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at');

        if ($this->filterType) { $query->where('type', $this->filterType); }
        if ($this->filterWallet) { 
            $query->where(function($q) {
                $q->where('wallet_id', $this->filterWallet)
                  ->orWhere('to_wallet_id', $this->filterWallet);
            });
        }
        if ($this->filterCategory) { $query->where('category_id', $this->filterCategory); }

        return view('livewire.transactions.transaction-list', [
            'transactions' => $query->paginate(15),
            'wallets' => Wallet::where('user_id', Auth::id())->get(),
            'categories' => Category::where('user_id', Auth::id())->orWhere('is_default', true)->get(),
        ])->layout('layouts.app');
    }

    public function delete($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);
        $transaction->delete();
        session()->flash('message', 'Transaksi berhasil dihapus.');
    }
}
