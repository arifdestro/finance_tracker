<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TransactionForm extends Component
{
    public $transactionId = null;
    public $type = 'expense';
    public $amount;
    public $wallet_id;
    public $to_wallet_id;
    public $category_id;
    public $transaction_date;
    public $description;
    public $notes;

    public $wallets;
    public $categories;

    public function mount($id = null)
    {
        $this->wallets = Wallet::where('user_id', Auth::id())->get();
        // default wallet
        if ($this->wallets->isNotEmpty()) {
            $default = collect($this->wallets)->firstWhere('is_default', true) ?? $this->wallets->first();
            $this->wallet_id = $default->id;
        }

        $this->transaction_date = date('Y-m-d');

        if ($id) {
            $trx = Transaction::where('user_id', Auth::id())->findOrFail($id);
            $this->transactionId = $trx->id;
            $this->type = $trx->type;
            $this->amount = round($trx->amount);
            $this->wallet_id = $trx->wallet_id;
            $this->to_wallet_id = $trx->to_wallet_id;
            $this->category_id = $trx->category_id;
            $this->transaction_date = $trx->transaction_date->format('Y-m-d');
            $this->description = $trx->description;
            $this->notes = $trx->notes;
        }
    }

    public function render()
    {
        // filter categories by type
        $this->categories = Category::where(function($q) {
                $q->where('user_id', Auth::id())->orWhere('is_default', true);
            })
            ->where('type', $this->type)
            ->get();

        return view('livewire.transactions.transaction-form')->layout('layouts.app');
    }

    public function updatedType()
    {
        $this->category_id = null; // reset category when type changes
    }

    public function save()
    {
        $rules = [
            'type' => 'required|in:income,expense,transfer',
            'amount' => 'required|numeric|min:1',
            'wallet_id' => 'required|exists:wallets,id',
            'transaction_date' => 'required|date',
            'description' => 'required|string|max:255',
        ];

        if ($this->type === 'transfer') {
            $rules['to_wallet_id'] = 'required|exists:wallets,id|different:wallet_id';
        } else {
            $rules['category_id'] = 'required|exists:categories,id';
        }

        $this->validate($rules);

        Transaction::updateOrCreate(['id' => $this->transactionId], [
            'user_id' => Auth::id(),
            'type' => $this->type,
            'amount' => $this->amount,
            'wallet_id' => $this->wallet_id,
            'to_wallet_id' => $this->type === 'transfer' ? $this->to_wallet_id : null,
            'category_id' => $this->type === 'transfer' ? null : $this->category_id,
            'transaction_date' => $this->transaction_date,
            'description' => $this->description,
            'notes' => $this->notes,
        ]);

        session()->flash('message', $this->transactionId ? 'Transaksi diperbarui.' : 'Transaksi berhasil dicatat.');
        return $this->redirectRoute('transactions.index', navigate: true);
    }
}
