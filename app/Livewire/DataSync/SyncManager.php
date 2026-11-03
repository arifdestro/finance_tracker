<?php

namespace App\Livewire\DataSync;

use App\Models\Budget;
use App\Models\Category;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class SyncManager extends Component
{
    use WithFileUploads;

    public $backupFile;
    public $importStatus = '';
    public $isImporting = false;

    public function render()
    {
        return view('livewire.data-sync.sync-manager')->layout('layouts.app');
    }

    public function exportData()
    {
        $userId = Auth::id();

        $data = [
            'version' => '1.0',
            'export_date' => now()->toIso8601String(),
            'wallets' => Wallet::where('user_id', $userId)->get()->toArray(),
            'categories' => Category::where('user_id', $userId)->get()->toArray(),
            'budgets' => Budget::where('user_id', $userId)->get()->toArray(),
            'recurring_transactions' => RecurringTransaction::where('user_id', $userId)->get()->toArray(),
            'transactions' => Transaction::where('user_id', $userId)->get()->toArray(),
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT);
        $filename = 'finance_backup_' . date('Y_m_d_His') . '.json';

        return response()->streamDownload(function () use ($json) {
            echo $json;
        }, $filename);
    }

    public function importData()
    {
        $this->validate([
            'backupFile' => 'required|file|mimetypes:application/json,text/plain|max:10240', // max 10MB
        ]);

        $this->isImporting = true;
        $this->importStatus = 'Memulai proses impor...';

        try {
            $jsonContent = file_get_contents($this->backupFile->getRealPath());
            $data = json_decode($jsonContent, true);

            if (!$data || !isset($data['version'])) {
                throw new \Exception('Format file backup tidak valid.');
            }

            $userId = Auth::id();

            DB::beginTransaction();

            // Clear existing data
            Transaction::where('user_id', $userId)->delete();
            RecurringTransaction::where('user_id', $userId)->delete();
            Budget::where('user_id', $userId)->delete();
            Category::where('user_id', $userId)->delete();
            Wallet::where('user_id', $userId)->delete();

            // Import Wallets
            $walletMap = [];
            if (!empty($data['wallets'])) {
                foreach ($data['wallets'] as $wallet) {
                    $newWallet = Wallet::create([
                        'user_id' => $userId,
                        'name' => $wallet['name'],
                        'type' => $wallet['type'],
                        'balance' => $wallet['balance'],
                        'is_default' => $wallet['is_default'],
                    ]);
                    $walletMap[$wallet['id']] = $newWallet->id;
                }
            }

            // Import Categories
            $categoryMap = [];
            if (!empty($data['categories'])) {
                foreach ($data['categories'] as $category) {
                    $newCategory = Category::create([
                        'user_id' => $userId,
                        'name' => $category['name'],
                        'type' => $category['type'],
                        'color' => $category['color'],
                        'icon' => $category['icon'],
                        'is_default' => $category['is_default'],
                    ]);
                    $categoryMap[$category['id']] = $newCategory->id;
                }
            }

            // Import Budgets
            if (!empty($data['budgets'])) {
                foreach ($data['budgets'] as $budget) {
                    if (isset($categoryMap[$budget['category_id']])) {
                        Budget::create([
                            'user_id' => $userId,
                            'category_id' => $categoryMap[$budget['category_id']],
                            'amount' => $budget['amount'],
                            'month' => $budget['month'],
                            'year' => $budget['year'],
                        ]);
                    }
                }
            }

            // Import Recurring Transactions
            if (!empty($data['recurring_transactions'])) {
                foreach ($data['recurring_transactions'] as $rt) {
                    if (isset($walletMap[$rt['wallet_id']])) {
                        RecurringTransaction::create([
                            'user_id' => $userId,
                            'wallet_id' => $walletMap[$rt['wallet_id']],
                            'category_id' => $rt['category_id'] && isset($categoryMap[$rt['category_id']]) ? $categoryMap[$rt['category_id']] : null,
                            'type' => $rt['type'],
                            'amount' => $rt['amount'],
                            'description' => $rt['description'],
                            'to_wallet_id' => $rt['to_wallet_id'] && isset($walletMap[$rt['to_wallet_id']]) ? $walletMap[$rt['to_wallet_id']] : null,
                            'frequency' => $rt['frequency'],
                            'next_date' => $rt['next_date'],
                            'is_active' => $rt['is_active'],
                        ]);
                    }
                }
            }

            // Import Transactions using insert to bypass observers (balances already set)
            if (!empty($data['transactions'])) {
                $transactionBatch = [];
                foreach ($data['transactions'] as $tx) {
                    if (isset($walletMap[$tx['wallet_id']])) {
                        $transactionBatch[] = [
                            'user_id' => $userId,
                            'wallet_id' => $walletMap[$tx['wallet_id']],
                            'category_id' => $tx['category_id'] && isset($categoryMap[$tx['category_id']]) ? $categoryMap[$tx['category_id']] : null,
                            'type' => $tx['type'],
                            'amount' => $tx['amount'],
                            'description' => $tx['description'],
                            'transaction_date' => $tx['transaction_date'],
                            'to_wallet_id' => $tx['to_wallet_id'] && isset($walletMap[$tx['to_wallet_id']]) ? $walletMap[$tx['to_wallet_id']] : null,
                            'notes' => $tx['notes'],
                            'created_at' => $tx['created_at'],
                            'updated_at' => $tx['updated_at'],
                        ];
                    }
                }
                
                foreach (array_chunk($transactionBatch, 500) as $chunk) {
                    Transaction::insert($chunk);
                }
            }

            DB::commit();
            
            $this->importStatus = '';
            session()->flash('message', 'Import data berhasil diselesaikan! Data keuangan Anda telah dipulihkan sepenuhnya.');
            $this->backupFile = null;
            
            $this->dispatch('dataImported');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import Backup Error: ' . $e->getMessage());
            $this->importStatus = '';
            $this->addError('backupFile', 'Gagal memulihkan data. Pastikan format file sesuai.');
        }

        $this->isImporting = false;
    }
}
