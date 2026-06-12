<?php

namespace App\Livewire\Budgets;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BudgetManager extends Component
{
    public $month;
    public $year;

    public $budgetId = null;
    public $category_id = '';
    public $amount = '';
    public $isModalOpen = false;

    public function mount()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
    }

    public function render()
    {
        $userId = Auth::id();

        // Get budgets for selected month
        $budgets = Budget::with('category')
            ->where('user_id', $userId)
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->get();

        // Calculate actual expense for each budget category
        $budgetsData = $budgets->map(function ($budget) use ($userId) {
            $actual = Transaction::where('user_id', $userId)
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $this->month)
                ->whereYear('transaction_date', $this->year)
                ->sum('amount');

            $percentage = $budget->amount > 0 ? ($actual / $budget->amount) * 100 : 0;
            
            return [
                'id' => $budget->id,
                'category' => $budget->category,
                'budgeted' => $budget->amount,
                'actual' => $actual,
                'percentage' => min($percentage, 100),
                'raw_percentage' => $percentage,
            ];
        });

        // Categories available for new budget (exclude already budgeted)
        $existingCategoryIds = $budgets->pluck('category_id')->toArray();
        $availableCategories = Category::where(function($q) use ($userId) {
                $q->where('user_id', $userId)->orWhere('is_default', true);
            })
            ->where('type', 'expense')
            ->get();

        return view('livewire.budgets.budget-manager', [
            'budgetsData' => $budgetsData,
            'availableCategories' => $availableCategories,
            'existingCategoryIds' => $existingCategoryIds
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function edit($id)
    {
        $budget = Budget::where('user_id', Auth::id())->findOrFail($id);
        $this->budgetId = $budget->id;
        $this->category_id = $budget->category_id;
        $this->amount = $budget->amount;
        $this->openModal();
    }

    public function store()
    {
        $this->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:1',
        ]);

        if (!$this->budgetId) {
            $exists = Budget::where('user_id', Auth::id())
                ->where('category_id', $this->category_id)
                ->where('month', $this->month)
                ->where('year', $this->year)
                ->exists();
            
            if ($exists) {
                $this->addError('category_id', 'Kategori ini sudah memiliki anggaran di bulan ini.');
                return;
            }
        }

        Budget::updateOrCreate(
            ['id' => $this->budgetId],
            [
                'user_id' => Auth::id(),
                'category_id' => $this->category_id,
                'amount' => $this->amount,
                'month' => $this->month,
                'year' => $this->year,
            ]
        );

        session()->flash('message', $this->budgetId ? 'Anggaran diperbarui.' : 'Anggaran berhasil dibuat.');
        $this->closeModal();
    }

    public function delete($id)
    {
        Budget::where('user_id', Auth::id())->findOrFail($id)->delete();
        session()->flash('message', 'Anggaran berhasil dihapus.');
    }

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; $this->resetInputFields(); }
    private function resetInputFields() { $this->budgetId = null; $this->category_id = ''; $this->amount = ''; }
}
