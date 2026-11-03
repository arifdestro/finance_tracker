<?php

namespace App\Livewire\Payables;

use App\Models\Payable;
use App\Models\PayablePayment;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PayableManager extends Component
{
    public $payableId = null;
    public $wallet_id = '';
    public $lender_name = '';
    public $amount = '';
    public $description = '';
    public $payable_date = '';
    public $due_date = '';
    public $notes = '';
    public $isModalOpen = false;
    public $filter = 'unpaid'; // 'all', 'unpaid', 'paid'

    // Payment Modal Properties
    public $isPaymentModalOpen = false;
    public $payment_payable_id = null;
    public $payment_wallet_id = '';
    public $payment_amount = '';
    public $payment_date = '';
    public $payment_notes = '';
    public $payableForPayment = null;

    public function mount()
    {
        $this->payable_date = Carbon::now()->format('Y-m-d');
        $this->payment_date = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $userId = Auth::id();

        $query = Payable::with(['wallet', 'payments.wallet'])
            ->where('user_id', $userId);

        if ($this->filter === 'unpaid') {
            $query->where('is_paid', false);
        } elseif ($this->filter === 'paid') {
            $query->where('is_paid', true);
        }

        $payables = $query->orderByDesc('payable_date')->orderByDesc('created_at')->get();

        $totalUnpaid = Payable::where('user_id', $userId)
            ->where('is_paid', false)
            ->get()
            ->sum(function($payable) { return max(0, $payable->amount - $payable->paid_amount); });

        $totalPaid = Payable::where('user_id', $userId)->sum('paid_amount');

        $wallets = Wallet::where('user_id', $userId)->get();

        return view('livewire.payables.payable-manager', [
            'payables' => $payables,
            'totalUnpaid' => $totalUnpaid,
            'totalPaid' => $totalPaid,
            'wallets' => $wallets,
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->payable_date = Carbon::now()->format('Y-m-d');
        $this->openModal();
    }

    public function edit($id)
    {
        $payable = Payable::where('user_id', Auth::id())->findOrFail($id);
        $this->payableId = $payable->id;
        $this->wallet_id = $payable->wallet_id;
        $this->lender_name = $payable->lender_name;
        $this->amount = round($payable->amount);
        $this->description = $payable->description;
        $this->payable_date = $payable->payable_date->format('Y-m-d');
        $this->due_date = $payable->due_date ? $payable->due_date->format('Y-m-d') : '';
        $this->notes = $payable->notes;
        $this->openModal();
    }

    public function store()
    {
        $this->validate([
            'lender_name' => 'required|string|max:255',
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:1',
            'payable_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:payable_date',
        ]);

        $isNew = !$this->payableId;

        if ($isNew) {
            $wallet = Wallet::where('user_id', Auth::id())->findOrFail($this->wallet_id);
            $wallet->balance += $this->amount; // Add to wallet for Payables
            $wallet->save();

            Payable::create([
                'user_id' => Auth::id(),
                'wallet_id' => $this->wallet_id,
                'lender_name' => $this->lender_name,
                'amount' => $this->amount,
                'paid_amount' => 0,
                'description' => $this->description,
                'payable_date' => $this->payable_date,
                'due_date' => $this->due_date ?: null,
                'notes' => $this->notes,
            ]);
        } else {
            $payable = Payable::where('user_id', Auth::id())->findOrFail($this->payableId);
            $oldAmount = $payable->amount;
            
            $oldWalletId = $payable->wallet_id;
            
            $oldWallet = Wallet::find($oldWalletId);
            if ($oldWallet) {
                $oldWallet->balance -= $oldAmount; // Reverse previous addition
                $oldWallet->save();
            }

            $newWallet = Wallet::where('user_id', Auth::id())->findOrFail($this->wallet_id);
            $newWallet->balance += $this->amount; // Add new amount
            $newWallet->save();

            $payable->update([
                'wallet_id' => $this->wallet_id,
                'lender_name' => $this->lender_name,
                'amount' => $this->amount,
                'description' => $this->description,
                'payable_date' => $this->payable_date,
                'due_date' => $this->due_date ?: null,
                'notes' => $this->notes,
            ]);
            
            // Check if reducing amount automatically pays it off
            if ($payable->paid_amount >= $payable->amount && !$payable->is_paid) {
                $payable->update(['is_paid' => true, 'paid_date' => Carbon::now()->format('Y-m-d')]);
            } elseif ($payable->paid_amount < $payable->amount && $payable->is_paid) {
                $payable->update(['is_paid' => false, 'paid_date' => null]);
            }
        }

        session()->flash('message', $isNew ? __('Utang berhasil dicatat.') : __('Utang berhasil diperbarui.'));
        $this->closeModal();
    }

    public function markAsPaid($id)
    {
        $payable = Payable::where('user_id', Auth::id())->findOrFail($id);

        if (!$payable->is_paid) {
            $remaining = $payable->amount - $payable->paid_amount;
            
            if ($remaining > 0) {
                $wallet = Wallet::find($payable->wallet_id);
                if ($wallet) {
                    $wallet->balance -= $remaining; // Deduct from wallet for paying Payable
                    $wallet->save();
                }

                PayablePayment::create([
                    'payable_id' => $payable->id,
                    'wallet_id' => $payable->wallet_id,
                    'amount' => $remaining,
                    'payment_date' => Carbon::now()->format('Y-m-d'),
                    'notes' => 'Pelunasan penuh (Otomatis)',
                ]);
            }

            $payable->update([
                'is_paid' => true,
                'paid_amount' => $payable->amount,
                'paid_date' => Carbon::now()->format('Y-m-d'),
            ]);

            session()->flash('message', __('Utang ditandai lunas penuh!'));
        }
    }

    public function delete($id)
    {
        $payable = Payable::where('user_id', Auth::id())->findOrFail($id);

        // Reverse the original loan amount back from the source wallet
        $wallet = Wallet::find($payable->wallet_id);
        if ($wallet) {
            $wallet->balance -= $payable->amount; // Reverse Payable logic
            $wallet->save();
        }
        
        // Reverse all payments made (add the payments back to the wallets)
        foreach ($payable->payments as $payment) {
            $paymentWallet = Wallet::find($payment->wallet_id);
            if ($paymentWallet) {
                $paymentWallet->balance += $payment->amount; // Refund
                $paymentWallet->save();
            }
        }

        $payable->delete();
        session()->flash('message', __('Utang berhasil dihapus.'));
    }

    // --- Payment Methods ---

    public function openPaymentModal($payableId)
    {
        $this->payableForPayment = Payable::with(['payments.wallet'])->where('user_id', Auth::id())->findOrFail($payableId);
        $this->payment_payable_id = $payableId;
        $this->payment_wallet_id = $this->payableForPayment->wallet_id; // Default to original wallet
        $this->payment_amount = round(max(0, $this->payableForPayment->amount - $this->payableForPayment->paid_amount));
        $this->payment_date = Carbon::now()->format('Y-m-d');
        $this->payment_notes = '';
        $this->isPaymentModalOpen = true;
    }

    public function closePaymentModal()
    {
        $this->isPaymentModalOpen = false;
        $this->payableForPayment = null;
        $this->payment_payable_id = null;
        $this->payment_wallet_id = '';
        $this->payment_amount = '';
        $this->payment_date = '';
        $this->payment_notes = '';
    }

    public function storePayment()
    {
        $this->validate([
            'payment_payable_id' => 'required|exists:payables,id',
            'payment_wallet_id' => 'required|exists:wallets,id',
            'payment_amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
        ]);

        $payable = Payable::where('user_id', Auth::id())->findOrFail($this->payment_payable_id);
        $remaining = $payable->amount - $payable->paid_amount;
        
        $wallet = Wallet::where('user_id', Auth::id())->findOrFail($this->payment_wallet_id);
        $wallet->balance -= $this->payment_amount; // Deduct for paying Payable
        $wallet->save();

        PayablePayment::create([
            'payable_id' => $payable->id,
            'wallet_id' => $wallet->id,
            'amount' => $this->payment_amount,
            'payment_date' => $this->payment_date,
            'notes' => $this->payment_notes,
        ]);

        $newPaidAmount = $payable->paid_amount + $this->payment_amount;
        $isPaid = $newPaidAmount >= $payable->amount;

        $payable->update([
            'paid_amount' => $newPaidAmount,
            'is_paid' => $isPaid,
            'paid_date' => $isPaid ? Carbon::now()->format('Y-m-d') : null,
        ]);

        session()->flash('message', __('Pembayaran/cicilan berhasil dicatat.'));
        $this->closePaymentModal();
    }

    public function deletePayment($paymentId)
    {
        $payment = PayablePayment::findOrFail($paymentId);
        $payable = Payable::where('user_id', Auth::id())->findOrFail($payment->payable_id);

        $wallet = Wallet::find($payment->wallet_id);
        if ($wallet) {
            $wallet->balance += $payment->amount; // Refund deleted payment
            $wallet->save();
        }

        $newPaidAmount = max(0, $payable->paid_amount - $payment->amount);
        $payable->update([
            'paid_amount' => $newPaidAmount,
            'is_paid' => $newPaidAmount >= $payable->amount,
            'paid_date' => ($newPaidAmount >= $payable->amount) ? $payable->paid_date : null,
        ]);

        $payment->delete();
        
        // Refresh the modal data if it's open
        if ($this->isPaymentModalOpen && $this->payableForPayment && $this->payableForPayment->id === $payable->id) {
            $this->openPaymentModal($payable->id);
        }

        session()->flash('message', __('Pembayaran berhasil dibatalkan.'));
    }

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; $this->resetInputFields(); }

    private function resetInputFields()
    {
        $this->payableId = null;
        $this->wallet_id = '';
        $this->lender_name = '';
        $this->amount = '';
        $this->description = '';
        $this->payable_date = '';
        $this->due_date = '';
        $this->notes = '';
    }
}

