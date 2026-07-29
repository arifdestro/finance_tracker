<?php

namespace App\Livewire\Reports;

use App\Models\Transaction;
use App\Models\Debt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportManager extends Component
{
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    private function getReportData()
    {
        $userId = Auth::id();

        $transactions = Transaction::with(['category', 'wallet', 'toWallet'])
            ->where('user_id', $userId)
            ->whereBetween('transaction_date', [$this->startDate, $this->endDate])
            ->orderBy('transaction_date')
            ->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');

        $expenseByCategory = Transaction::with('category')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$this->startDate, $this->endDate])
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->get();

        return [
            'transactions' => $transactions->reverse(),
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'expenseByCategory' => $expenseByCategory,
            'totalDebtUnpaid' => Debt::where('user_id', $userId)->where('is_paid', false)->sum('amount'),
            'totalDebtPaid' => Debt::where('user_id', $userId)->where('is_paid', true)->sum('amount'),
            'debts' => Debt::with('wallet')->where('user_id', $userId)
                ->whereBetween('debt_date', [$this->startDate, $this->endDate])
                ->orderByDesc('debt_date')->get(),
        ];
    }

    public function render()
    {
        return view('livewire.reports.report-manager', $this->getReportData())->layout('layouts.app');
    }

    public function exportCsv()
    {
        $data = $this->getReportData();
        $transactions = $data['transactions'];

        $csvData = "Tanggal,Tipe,Kategori,Dompet,Jumlah,Deskripsi\n";
        foreach ($transactions as $trx) {
            $catName = $trx->category ? $trx->category->name : ($trx->type == 'transfer' ? 'Transfer' : '-');
            $walletName = $trx->type == 'transfer' ? $trx->wallet->name . ' -> ' . $trx->toWallet?->name : $trx->wallet->name;
            $amount = $trx->type == 'expense' ? '-' . $trx->amount : $trx->amount;
            
            // Escape descriptions for CSV
            $desc = str_replace('"', '""', $trx->description);
            
            $csvData .= "{$trx->transaction_date->format('Y-m-d')},{$trx->type},\"{$catName}\",\"{$walletName}\",{$amount},\"{$desc}\"\n";
        }

        return response()->streamDownload(function() use ($csvData) {
            echo $csvData;
        }, 'Laporan_Keuangan_' . $this->startDate . '_sampai_' . $this->endDate . '.csv');
    }

    public function exportPdf()
    {
        $data = $this->getReportData();
        $data['startDate'] = $this->startDate;
        $data['endDate'] = $this->endDate;

        $pdf = Pdf::loadView('reports.pdf', $data);

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Keuangan_' . $this->startDate . '_sampai_' . $this->endDate . '.pdf');
    }
}
