<?php

namespace App\Livewire\Debts;

use App\Models\Debt;
use App\Models\DebtPayment;
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

    // Payment Modal Properties
    public $isPaymentModalOpen = false;
    public $payment_debt_id = null;
    public $payment_wallet_id = '';
    public $payment_amount = '';
    public $payment_date = '';
    public $payment_notes = '';
    public $debtForPayment = null;

    public function mount()
    {
        $this->debt_date = Carbon::now()->format('Y-m-d');
        $this->payment_date = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $userId = Auth::id();

        $query = Debt::with(['wallet', 'payments.wallet'])
            ->where('user_id', $userId);

        if ($this->filter === 'unpaid') {
            $query->where('is_paid', false);
        } elseif ($this->filter === 'paid') {
            $query->where('is_paid', true);
        }

        $debts = $query->orderByDesc('debt_date')->orderByDesc('created_at')->get();

        $totalUnpaid = Debt::where('user_id', $userId)
            ->where('is_paid', false)
            ->get()
            ->sum(function($debt) { return max(0, $debt->amount - $debt->paid_amount); });

        $totalPaid = Debt::where('user_id', $userId)->sum('paid_amount');

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
            $wallet = Wallet::where('user_id', Auth::id())->findOrFail($this->wallet_id);
            $wallet->balance -= $this->amount;
            $wallet->save();

            Debt::create([
                'user_id' => Auth::id(),
                'wallet_id' => $this->wallet_id,
                'borrower_name' => $this->borrower_name,
                'amount' => $this->amount,
                'paid_amount' => 0,
                'description' => $this->description,
                'debt_date' => $this->debt_date,
                'due_date' => $this->due_date ?: null,
                'notes' => $this->notes,
            ]);
        } else {
            $debt = Debt::where('user_id', Auth::id())->findOrFail($this->debtId);
            $oldAmount = $debt->amount;
            
            // Note: In real scenarios, editing the total amount of a debt that has payments 
            // can be tricky. We just update the debt's original balance impact.
            $oldWalletId = $debt->wallet_id;
            
            $oldWallet = Wallet::find($oldWalletId);
            if ($oldWallet) {
                $oldWallet->balance += $oldAmount;
                $oldWallet->save();
            }

            $newWallet = Wallet::where('user_id', Auth::id())->findOrFail($this->wallet_id);
            $newWallet->balance -= $this->amount;
            $newWallet->save();

            $debt->update([
                'wallet_id' => $this->wallet_id,
                'borrower_name' => $this->borrower_name,
                'amount' => $this->amount,
                'description' => $this->description,
                'debt_date' => $this->debt_date,
                'due_date' => $this->due_date ?: null,
                'notes' => $this->notes,
            ]);
            
            // Check if reducing amount automatically pays it off
            if ($debt->paid_amount >= $debt->amount && !$debt->is_paid) {
                $debt->update(['is_paid' => true, 'paid_date' => Carbon::now()->format('Y-m-d')]);
            } elseif ($debt->paid_amount < $debt->amount && $debt->is_paid) {
                $debt->update(['is_paid' => false, 'paid_date' => null]);
            }
        }

        session()->flash('message', $isNew ? __('Piutang berhasil dicatat.') : __('Piutang berhasil diperbarui.'));
        $this->closeModal();
    }

    public function markAsPaid($id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);

        if (!$debt->is_paid) {
            $remaining = $debt->amount - $debt->paid_amount;
            
            if ($remaining > 0) {
                $wallet = Wallet::find($debt->wallet_id);
                if ($wallet) {
                    $wallet->balance += $remaining;
                    $wallet->save();
                }

                DebtPayment::create([
                    'debt_id' => $debt->id,
                    'wallet_id' => $debt->wallet_id,
                    'amount' => $remaining,
                    'payment_date' => Carbon::now()->format('Y-m-d'),
                    'notes' => 'Pelunasan penuh (Otomatis)',
                ]);
            }

            $debt->update([
                'is_paid' => true,
                'paid_amount' => $debt->amount,
                'paid_date' => Carbon::now()->format('Y-m-d'),
            ]);

            session()->flash('message', __('Piutang ditandai lunas penuh!'));
        }
    }

    public function delete($id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);

        // Reverse the original loan amount back to the source wallet
        $wallet = Wallet::find($debt->wallet_id);
        if ($wallet) {
            $wallet->balance += $debt->amount;
            $wallet->save();
        }
        
        // Reverse all payments made (subtract the payments from the receiving wallets)
        foreach ($debt->payments as $payment) {
            $paymentWallet = Wallet::find($payment->wallet_id);
            if ($paymentWallet) {
                $paymentWallet->balance -= $payment->amount;
                $paymentWallet->save();
            }
        }

        $debt->delete();
        session()->flash('message', __('Piutang berhasil dihapus.'));
    }

    // --- Payment Methods ---

    public function openPaymentModal($debtId)
    {
        $this->debtForPayment = Debt::with(['payments.wallet'])->where('user_id', Auth::id())->findOrFail($debtId);
        $this->payment_debt_id = $debtId;
        $this->payment_wallet_id = $this->debtForPayment->wallet_id; // Default to original wallet
        $this->payment_amount = round(max(0, $this->debtForPayment->amount - $this->debtForPayment->paid_amount));
        $this->payment_date = Carbon::now()->format('Y-m-d');
        $this->payment_notes = '';
        $this->isPaymentModalOpen = true;
    }

    public function closePaymentModal()
    {
        $this->isPaymentModalOpen = false;
        $this->debtForPayment = null;
        $this->payment_debt_id = null;
        $this->payment_wallet_id = '';
        $this->payment_amount = '';
        $this->payment_date = '';
        $this->payment_notes = '';
    }

    public function storePayment()
    {
        $this->validate([
            'payment_debt_id' => 'required|exists:debts,id',
            'payment_wallet_id' => 'required|exists:wallets,id',
            'payment_amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
        ]);

        $debt = Debt::where('user_id', Auth::id())->findOrFail($this->payment_debt_id);
        $remaining = $debt->amount - $debt->paid_amount;
        
        // Allow overpaying? For now, let's limit it to the remaining amount or let it slide?
        // Let's allow exact or partial.

        $wallet = Wallet::where('user_id', Auth::id())->findOrFail($this->payment_wallet_id);
        $wallet->balance += $this->payment_amount;
        $wallet->save();

        DebtPayment::create([
            'debt_id' => $debt->id,
            'wallet_id' => $wallet->id,
            'amount' => $this->payment_amount,
            'payment_date' => $this->payment_date,
            'notes' => $this->payment_notes,
        ]);

        $newPaidAmount = $debt->paid_amount + $this->payment_amount;
        $isPaid = $newPaidAmount >= $debt->amount;

        $debt->update([
            'paid_amount' => $newPaidAmount,
            'is_paid' => $isPaid,
            'paid_date' => $isPaid ? Carbon::now()->format('Y-m-d') : null,
        ]);

        session()->flash('message', __('Pembayaran/cicilan berhasil dicatat.'));
        $this->closePaymentModal();
    }

    public function deletePayment($paymentId)
    {
        $payment = DebtPayment::findOrFail($paymentId);
        $debt = Debt::where('user_id', Auth::id())->findOrFail($payment->debt_id);

        $wallet = Wallet::find($payment->wallet_id);
        if ($wallet) {
            $wallet->balance -= $payment->amount;
            $wallet->save();
        }

        $newPaidAmount = max(0, $debt->paid_amount - $payment->amount);
        $debt->update([
            'paid_amount' => $newPaidAmount,
            'is_paid' => $newPaidAmount >= $debt->amount,
            'paid_date' => ($newPaidAmount >= $debt->amount) ? $debt->paid_date : null,
        ]);

        $payment->delete();
        
        // Refresh the modal data if it's open
        if ($this->isPaymentModalOpen && $this->debtForPayment && $this->debtForPayment->id === $debt->id) {
            $this->openPaymentModal($debt->id);
        }

        session()->flash('message', __('Pembayaran berhasil dibatalkan.'));
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

