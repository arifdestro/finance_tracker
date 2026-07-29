<?php

namespace App\Livewire\Debts;

use App\Models\Debt;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DebtManager extends Component
{
    public $debtId = null;
    public $wallet_id = '';
    public $borrower_name = '';
    public $amount = '';
    public $description = '';
    public $debt_date = '';
    public $due_date = '';
    public $notes = '';
    public $isModalOpen = false;
    public $filter = 'unpaid'; // 'all', 'unpaid', 'paid'

    public function mount()
    {
        $this->debt_date = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $userId = Auth::id();

        $query = Debt::with('wallet')
            ->where('user_id', $userId);

        if ($this->filter === 'unpaid') {
            $query->where('is_paid', false);
        } elseif ($this->filter === 'paid') {
            $query->where('is_paid', true);
        }

        $debts = $query->orderByDesc('debt_date')->orderByDesc('created_at')->get();

        $totalUnpaid = Debt::where('user_id', $userId)->where('is_paid', false)->sum('amount');
        $totalPaid = Debt::where('user_id', $userId)->where('is_paid', true)->sum('amount');

        $wallets = Wallet::where('user_id', $userId)->get();

        return view('livewire.debts.debt-manager', [
            'debts' => $debts,
            'totalUnpaid' => $totalUnpaid,
            'totalPaid' => $totalPaid,
            'wallets' => $wallets,
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->debt_date = Carbon::now()->format('Y-m-d');
        $this->openModal();
    }

    public function edit($id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);
        $this->debtId = $debt->id;
        $this->wallet_id = $debt->wallet_id;
        $this->borrower_name = $debt->borrower_name;
        $this->amount = round($debt->amount);
        $this->description = $debt->description;
        $this->debt_date = $debt->debt_date->format('Y-m-d');
        $this->due_date = $debt->due_date ? $debt->due_date->format('Y-m-d') : '';
        $this->notes = $debt->notes;
        $this->openModal();
    }

    public function store()
    {
        $this->validate([
            'borrower_name' => 'required|string|max:255',
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:1',
            'debt_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:debt_date',
        ]);

        $isNew = !$this->debtId;

        if ($isNew) {
            // New debt: subtract from wallet
            $wallet = Wallet::where('user_id', Auth::id())->findOrFail($this->wallet_id);
            $wallet->balance -= $this->amount;
            $wallet->save();

            Debt::create([
                'user_id' => Auth::id(),
                'wallet_id' => $this->wallet_id,
                'borrower_name' => $this->borrower_name,
                'amount' => $this->amount,
                'description' => $this->description,
                'debt_date' => $this->debt_date,
                'due_date' => $this->due_date ?: null,
                'notes' => $this->notes,
            ]);
        } else {
            // Editing existing debt
            $debt = Debt::where('user_id', Auth::id())->findOrFail($this->debtId);
            $oldAmount = $debt->amount;
            $oldWalletId = $debt->wallet_id;
            $wasPaid = $debt->is_paid;

            // If not paid, reverse old amount from old wallet, apply new amount to new wallet
            if (!$wasPaid) {
                $oldWallet = Wallet::find($oldWalletId);
                if ($oldWallet) {
                    $oldWallet->balance += $oldAmount;
                    $oldWallet->save();
                }

                $newWallet = Wallet::where('user_id', Auth::id())->findOrFail($this->wallet_id);
                $newWallet->balance -= $this->amount;
                $newWallet->save();
            }

            $debt->update([
                'wallet_id' => $this->wallet_id,
                'borrower_name' => $this->borrower_name,
                'amount' => $this->amount,
                'description' => $this->description,
                'debt_date' => $this->debt_date,
                'due_date' => $this->due_date ?: null,
                'notes' => $this->notes,
            ]);
        }

        session()->flash('message', $isNew ? __('Piutang berhasil dicatat.') : __('Piutang berhasil diperbarui.'));
        $this->closeModal();
    }

    public function markAsPaid($id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);

        if (!$debt->is_paid) {
            // Return money to wallet
            $wallet = Wallet::find($debt->wallet_id);
            if ($wallet) {
                $wallet->balance += $debt->amount;
                $wallet->save();
            }

            $debt->update([
                'is_paid' => true,
                'paid_date' => Carbon::now()->format('Y-m-d'),
            ]);

            session()->flash('message', __('Piutang ditandai lunas!'));
        }
    }

    public function markAsUnpaid($id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);

        if ($debt->is_paid) {
            // Subtract from wallet again
            $wallet = Wallet::find($debt->wallet_id);
            if ($wallet) {
                $wallet->balance -= $debt->amount;
                $wallet->save();
            }

            $debt->update([
                'is_paid' => false,
                'paid_date' => null,
            ]);

            session()->flash('message', __('Piutang ditandai belum lunas.'));
        }
    }

    public function delete($id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);

        // If debt is not paid, return the money to wallet
        if (!$debt->is_paid) {
            $wallet = Wallet::find($debt->wallet_id);
            if ($wallet) {
                $wallet->balance += $debt->amount;
                $wallet->save();
            }
        }

        $debt->delete();
        session()->flash('message', __('Piutang berhasil dihapus.'));
    }

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; $this->resetInputFields(); }

    private function resetInputFields()
    {
        $this->debtId = null;
        $this->wallet_id = '';
        $this->borrower_name = '';
        $this->amount = '';
        $this->description = '';
        $this->debt_date = '';
        $this->due_date = '';
        $this->notes = '';
    }
}
