<?php

namespace App\Livewire\Transactions;

use App\Models\Wallet;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class TransactionImporter extends Component
{
    use WithFileUploads;

    public $step = 1;
    public $file;
    public $wallet_id;
    public $wallets;

    public $csvHeaders = [];
    public $csvPreview = [];
    public $csvFilePath = '';

    // Column Mapping
    public $mapDate = '';
    public $mapDescription = '';
    public $mapAmount = '';
    public $mapType = '';

    public $importedCount = 0;
    public $importError = '';

    public function mount()
    {
        $this->wallets = Wallet::where('user_id', Auth::id())->get();
        if ($this->wallets->isNotEmpty()) {
            $default = collect($this->wallets)->firstWhere('is_default', true) ?? $this->wallets->first();
            $this->wallet_id = $default->id;
        }
    }

    public function render()
    {
        return view('livewire.transactions.transaction-importer')->layout('layouts.app');
    }

    public function processFile()
    {
        $this->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'file' => 'required|file|mimes:csv,txt|max:5120', // 5MB Max
        ]);

        try {
            $path = $this->file->store('csv_imports');
            $this->csvFilePath = storage_path('app/' . $path);

            $fileHandle = fopen($this->csvFilePath, 'r');
            if ($fileHandle !== false) {
                // Get headers
                $this->csvHeaders = fgetcsv($fileHandle);
                
                // Get 3 rows for preview
                $count = 0;
                while (($data = fgetcsv($fileHandle)) !== false && $count < 3) {
                    $this->csvPreview[] = $data;
                    $count++;
                }
                fclose($fileHandle);
            }

            // Auto-guess columns based on common names
            $this->autoMapColumns();

            $this->step = 2;
        } catch (\Exception $e) {
            $this->importError = __('Gagal membaca file: ') . $e->getMessage();
        }
    }

    private function autoMapColumns()
    {
        foreach ($this->csvHeaders as $index => $header) {
            $lower = strtolower($header);
            if (str_contains($lower, 'tanggal') || str_contains($lower, 'date')) {
                $this->mapDate = (string)$index;
            } elseif (str_contains($lower, 'keterangan') || str_contains($lower, 'desc') || str_contains($lower, 'nama')) {
                $this->mapDescription = (string)$index;
            } elseif (str_contains($lower, 'nominal') || str_contains($lower, 'jumlah') || str_contains($lower, 'amount') || str_contains($lower, 'saldo')) {
                $this->mapAmount = (string)$index;
            } elseif (str_contains($lower, 'tipe') || str_contains($lower, 'type') || str_contains($lower, 'mutasi') || str_contains($lower, 'cr/db')) {
                $this->mapType = (string)$index;
            }
        }
    }

    public function performImport()
    {
        $this->validate([
            'mapDate' => 'required|numeric',
            'mapDescription' => 'required|numeric',
            'mapAmount' => 'required|numeric',
            // mapType is optional, we can deduce from Amount sign
        ]);

        try {
            $fileHandle = fopen($this->csvFilePath, 'r');
            if ($fileHandle === false) {
                throw new \Exception("File tidak dapat dibaca.");
            }

            // Skip header
            fgetcsv($fileHandle);

            $expenseCategory = Category::firstOrCreate(
                ['user_id' => Auth::id(), 'type' => 'expense', 'name' => 'Lain-Lain (Impor)'],
                ['icon' => '📦', 'color' => '#64748b']
            );

            $incomeCategory = Category::firstOrCreate(
                ['user_id' => Auth::id(), 'type' => 'income', 'name' => 'Pendapatan Lain (Impor)'],
                ['icon' => '💰', 'color' => '#10b981']
            );

            $wallet = Wallet::where('user_id', Auth::id())->findOrFail($this->wallet_id);
            $totalAdded = 0;
            $balanceChange = 0;

            while (($data = fgetcsv($fileHandle)) !== false) {
                // Check if row has enough columns
                if (count($data) <= max((int)$this->mapDate, (int)$this->mapDescription, (int)$this->mapAmount)) {
                    continue;
                }

                $rawDate = $data[(int)$this->mapDate];
                $rawDesc = $data[(int)$this->mapDescription];
                $rawAmount = $data[(int)$this->mapAmount];
                
                // Parse Amount (strip non-numeric except minus)
                // Assuming Indonesian format: remove dots, keep minus.
                // E.g. "1.500.000,00" -> strip ",00" first, then strip dots.
                // Simple regex: strip everything after comma, then strip non digits.
                $cleanAmount = preg_replace('/,.*/', '', $rawAmount); // remove decimals if any
                $cleanAmount = preg_replace('/[^0-9-]/', '', $cleanAmount);
                $amount = (float) $cleanAmount;

                // Determine Type
                $type = 'expense';
                if ($this->mapType !== '' && isset($data[(int)$this->mapType])) {
                    $rawType = strtolower(trim($data[(int)$this->mapType]));
                    if ($rawType === 'cr' || $rawType === 'kredit' || $rawType === 'income' || str_contains($rawType, 'masuk')) {
                        $type = 'income';
                    } elseif ($rawType === 'db' || $rawType === 'debit' || $rawType === 'expense' || str_contains($rawType, 'keluar')) {
                        $type = 'expense';
                    } else {
                        // Fallback to amount sign
                        $type = $amount >= 0 ? 'income' : 'expense';
                    }
                } else {
                    $type = $amount >= 0 ? 'income' : 'expense';
                }

                $amount = abs($amount);

                if ($amount == 0) continue; // Skip zero mutations

                // Parse Date (try to catch common formats)
                try {
                    $date = Carbon::parse($rawDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    $date = Carbon::now()->format('Y-m-d');
                }

                Transaction::create([
                    'user_id' => Auth::id(),
                    'wallet_id' => $wallet->id,
                    'category_id' => $type === 'income' ? $incomeCategory->id : $expenseCategory->id,
                    'type' => $type,
                    'amount' => $amount,
                    'description' => substr(trim($rawDesc), 0, 255),
                    'transaction_date' => $date,
                ]);

                if ($type === 'income') {
                    $balanceChange += $amount;
                } else {
                    $balanceChange -= $amount;
                }

                $totalAdded++;
            }

            fclose($fileHandle);
            @unlink($this->csvFilePath);

            // Update Wallet Balance
            $wallet->balance += $balanceChange;
            $wallet->save();

            $this->importedCount = $totalAdded;
            $this->step = 3;

        } catch (\Exception $e) {
            $this->importError = __('Gagal memproses impor: ') . $e->getMessage();
            $this->step = 1;
        }
    }

    public function resetImporter()
    {
        $this->reset(['step', 'file', 'csvHeaders', 'csvPreview', 'csvFilePath', 'mapDate', 'mapDescription', 'mapAmount', 'mapType', 'importedCount', 'importError']);
    }
}
