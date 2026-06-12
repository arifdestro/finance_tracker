<?php

namespace App\Livewire\RecurringTransactions;

use App\Models\RecurringTransaction;
use App\Models\Wallet;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class RecurringManager extends Component
{
    use WithPagination;

    public $rtId = null;
    public $type = 'expense';
    public $amount;
    public $wallet_id;
    public $to_wallet_id;
    public $category_id;
    public $description;
    public $frequency = 'monthly';
    public $next_date;
    public $is_active = true;
    public $isModalOpen = false;

    public $wallets;
    public $categories;

    public function mount()
    {
        $this->wallets = Wallet::where('user_id', Auth::id())->get();
        if ($this->wallets->isNotEmpty()) {
            $default = collect($this->wallets)->firstWhere('is_default', true) ?? $this->wallets->first();
            $this->wallet_id = $default->id;
        }
        $this->next_date = date('Y-m-d');
    }

    public function render()
    {
        $this->categories = Category::where(function($q) {
                $q->where('user_id', Auth::id())->orWhere('is_default', true);
            })
            ->where('type', $this->type)
            ->get();

        $recurringTransactions = RecurringTransaction::with(['category', 'wallet', 'toWallet'])
            ->where('user_id', Auth::id())
            ->orderBy('next_date')
            ->paginate(10);

        return view('livewire.recurring-transactions.recurring-manager', compact('recurringTransactions'))
            ->layout('layouts.app');
    }

    public function updatedType()
    {
        $this->category_id = null;
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function edit($id)
    {
        $rt = RecurringTransaction::where('user_id', Auth::id())->findOrFail($id);
        $this->rtId = $rt->id;
        $this->type = $rt->type;
        $this->amount = $rt->amount;
        $this->wallet_id = $rt->wallet_id;
        $this->to_wallet_id = $rt->to_wallet_id;
        $this->category_id = $rt->category_id;
        $this->description = $rt->description;
        $this->frequency = $rt->frequency;
        $this->next_date = $rt->next_date->format('Y-m-d');
        $this->is_active = $rt->is_active;

        $this->openModal();
    }

    public function toggleActive($id)
    {
        $rt = RecurringTransaction::where('user_id', Auth::id())->findOrFail($id);
        $rt->is_active = !$rt->is_active;
        $rt->save();
        session()->flash('message', 'Status transaksi berulang diubah.');
    }

    public function store()
    {
        $rules = [
            'type' => 'required|in:income,expense,transfer',
            'amount' => 'required|numeric|min:1',
            'wallet_id' => 'required|exists:wallets,id',
            'description' => 'required|string|max:255',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'next_date' => 'required|date',
        ];

        if ($this->type === 'transfer') {
            $rules['to_wallet_id'] = 'required|exists:wallets,id|different:wallet_id';
        } else {
            $rules['category_id'] = 'required|exists:categories,id';
        }

        $this->validate($rules);

        RecurringTransaction::updateOrCreate(
            ['id' => $this->rtId],
            [
                'user_id' => Auth::id(),
                'type' => $this->type,
                'amount' => $this->amount,
                'wallet_id' => $this->wallet_id,
                'to_wallet_id' => $this->type === 'transfer' ? $this->to_wallet_id : null,
                'category_id' => $this->type === 'transfer' ? null : $this->category_id,
                'description' => $this->description,
                'frequency' => $this->frequency,
                'next_date' => $this->next_date,
                'is_active' => $this->is_active,
            ]
        );

        session()->flash('message', $this->rtId ? 'Diperbarui.' : 'Transaksi berulang berhasil ditambahkan.');
        $this->closeModal();
    }

    public function delete($id)
    {
        RecurringTransaction::where('user_id', Auth::id())->findOrFail($id)->delete();
        session()->flash('message', 'Dihapus.');
    }

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; $this->resetInputFields(); }

    private function resetInputFields()
    {
        $this->rtId = null;
        $this->amount = '';
        $this->category_id = '';
        $this->description = '';
        $this->is_active = true;
    }
}
