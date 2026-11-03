<?php

namespace App\Livewire\Goals;

use App\Models\Goal;
use App\Models\GoalTransaction;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GoalManager extends Component
{
    // Goal Properties
    public $goalId = null;
    public $name = '';
    public $target_amount = '';
    public $target_date = '';
    public $color = 'emerald';
    public $notes = '';

    // Modal States
    public $isGoalModalOpen = false;
    public $isTransactionModalOpen = false;

    // Transaction Properties
    public $transactionType = 'deposit'; // 'deposit' or 'withdraw'
    public $transactionGoalId = null;
    public $wallet_id = '';
    public $transactionAmount = '';
    public $transactionDate = '';
    public $transactionDescription = '';
    public $goalForTransaction = null;

    public $colors = ['emerald', 'indigo', 'rose', 'amber', 'sky', 'violet'];

    public function render()
    {
        $userId = Auth::id();
        $goals = Goal::where('user_id', $userId)
            ->with('transactions.wallet')
            ->orderByDesc('created_at')
            ->get();
        
        $wallets = Wallet::where('user_id', $userId)->get();

        return view('livewire.goals.goal-manager', compact('goals', 'wallets'))->layout('layouts.app');
    }

    // --- Goal CRUD ---

    public function openGoalModal()
    {
        $this->resetGoalForm();
        $this->isGoalModalOpen = true;
    }

    public function closeGoalModal()
    {
        $this->isGoalModalOpen = false;
    }

    private function resetGoalForm()
    {
        $this->goalId = null;
        $this->name = '';
        $this->target_amount = '';
        $this->target_date = '';
        $this->color = $this->colors[array_rand($this->colors)];
        $this->notes = '';
    }

    public function editGoal($id)
    {
        $goal = Goal::where('user_id', Auth::id())->findOrFail($id);
        $this->goalId = $goal->id;
        $this->name = $goal->name;
        $this->target_amount = round($goal->target_amount);
        $this->target_date = $goal->target_date ? $goal->target_date->format('Y-m-d') : '';
        $this->color = $goal->color;
        $this->notes = $goal->notes;
        $this->isGoalModalOpen = true;
    }

    public function storeGoal()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'target_date' => 'nullable|date',
            'color' => 'required|string',
        ]);

        if ($this->goalId) {
            $goal = Goal::where('user_id', Auth::id())->findOrFail($this->goalId);
            $goal->update([
                'name' => $this->name,
                'target_amount' => $this->target_amount,
                'target_date' => $this->target_date ?: null,
                'color' => $this->color,
                'notes' => $this->notes,
            ]);
            session()->flash('message', __('Target berhasil diperbarui.'));
        } else {
            Goal::create([
                'user_id' => Auth::id(),
                'name' => $this->name,
                'target_amount' => $this->target_amount,
                'current_amount' => 0,
                'target_date' => $this->target_date ?: null,
                'color' => $this->color,
                'notes' => $this->notes,
            ]);
            session()->flash('message', __('Target baru berhasil dibuat.'));
        }

        $this->closeGoalModal();
    }

    public function deleteGoal($id)
    {
        $goal = Goal::where('user_id', Auth::id())->findOrFail($id);

        // Refund all deposited money back to original wallets
        foreach ($goal->transactions as $trx) {
            $wallet = Wallet::find($trx->wallet_id);
            if ($wallet) {
                if ($trx->type === 'deposit') {
                    $wallet->balance += $trx->amount;
                } else {
                    $wallet->balance -= $trx->amount;
                }
                $wallet->save();
            }
        }

        $goal->delete();
        session()->flash('message', __('Target beserta riwayat dananya berhasil dihapus dan dikembalikan.'));
    }

    // --- Goal Transactions (Deposit / Withdraw) ---

    public function openTransactionModal($goalId, $type = 'deposit')
    {
        $this->goalForTransaction = Goal::where('user_id', Auth::id())->findOrFail($goalId);
        $this->transactionGoalId = $goalId;
        $this->transactionType = $type;
        $this->transactionAmount = '';
        $this->transactionDate = Carbon::now()->format('Y-m-d');
        $this->transactionDescription = '';
        $this->wallet_id = '';
        
        $this->isTransactionModalOpen = true;
    }

    public function closeTransactionModal()
    {
        $this->isTransactionModalOpen = false;
        $this->goalForTransaction = null;
    }

    public function storeTransaction()
    {
        $this->validate([
            'transactionGoalId' => 'required|exists:goals,id',
            'wallet_id' => 'required|exists:wallets,id',
            'transactionAmount' => 'required|numeric|min:1',
            'transactionDate' => 'required|date',
        ]);

        $goal = Goal::where('user_id', Auth::id())->findOrFail($this->transactionGoalId);
        $wallet = Wallet::where('user_id', Auth::id())->findOrFail($this->wallet_id);

        if ($this->transactionType === 'withdraw') {
            if ($goal->current_amount < $this->transactionAmount) {
                $this->addError('transactionAmount', __('Jumlah penarikan melebihi saldo target saat ini.'));
                return;
            }
            // Move money from Goal to Wallet
            $goal->current_amount -= $this->transactionAmount;
            $wallet->balance += $this->transactionAmount;
        } else {
            // Move money from Wallet to Goal
            if ($wallet->balance < $this->transactionAmount) {
                $this->addError('transactionAmount', __('Saldo dompet tidak mencukupi untuk ditabung.'));
                return;
            }
            $goal->current_amount += $this->transactionAmount;
            $wallet->balance -= $this->transactionAmount;
        }

        $goal->save();
        $wallet->save();

        GoalTransaction::create([
            'goal_id' => $goal->id,
            'wallet_id' => $wallet->id,
            'amount' => $this->transactionAmount,
            'type' => $this->transactionType,
            'date' => $this->transactionDate,
            'description' => $this->transactionDescription,
        ]);

        session()->flash('message', $this->transactionType === 'deposit' ? __('Uang berhasil ditabung ke target!') : __('Uang berhasil ditarik dari target.'));
        $this->closeTransactionModal();
    }
}
