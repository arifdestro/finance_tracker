<?php

namespace App\Livewire\Dashboard;

use App\Models\Transaction;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Overview extends Component
{
    public function render()
    {
        $userId = Auth::id();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Summaries
        $totalBalance = Wallet::where('user_id', $userId)->sum('balance');
        $incomeThisMonth = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');
            
        $expenseThisMonth = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        // Recent Transactions
        $recentTransactions = Transaction::with(['category', 'wallet', 'toWallet'])
            ->where('user_id', $userId)
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Chart Data: Expense by Category (Doughnut)
        $expenseByCategory = Transaction::with('category')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->groupBy('category_id')
            ->get();

        $categoryLabels = [];
        $categoryData = [];
        $categoryColors = [];

        foreach ($expenseByCategory as $expense) {
            $categoryLabels[] = $expense->category ? $expense->category->name : 'Lain-lain';
            $categoryData[] = $expense->total;
            $categoryColors[] = $expense->category ? $expense->category->color : '#9ca3af';
        }

        // Chart Data: Income vs Expense Last 7 Days (Line/Bar)
        $last7Days = collect();
        $incomeData = [];
        $expenseData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labelDate = Carbon::now()->subDays($i)->format('d M');
            $last7Days->push($labelDate);

            $income = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereDate('transaction_date', $date)
                ->sum('amount');
                
            $expense = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereDate('transaction_date', $date)
                ->sum('amount');

            $incomeData[] = $income;
            $expenseData[] = $expense;
        }

        return view('livewire.dashboard.overview', compact(
            'totalBalance', 'incomeThisMonth', 'expenseThisMonth', 'recentTransactions',
            'categoryLabels', 'categoryData', 'categoryColors',
            'last7Days', 'incomeData', 'expenseData'
        ))->layout('layouts.app');
    }
}
