<?php

namespace App\Console\Commands;

use App\Models\RecurringTransaction;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessRecurringTransactions extends Command
{
    protected $signature = 'finance:process-recurring';
    protected $description = 'Process recurring transactions that are due today';

    public function handle()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        $dueTransactions = RecurringTransaction::where('is_active', true)
            ->whereDate('next_date', '<=', $today)
            ->get();

        if ($dueTransactions->isEmpty()) {
            $this->info('No recurring transactions due today.');
            return;
        }

        $count = 0;
        foreach ($dueTransactions as $rt) {
            DB::transaction(function () use ($rt, $today) {
                // Determine transaction date (use the due date, not today, in case script missed a day)
                $txDate = $rt->next_date;

                Transaction::create([
                    'user_id' => $rt->user_id,
                    'wallet_id' => $rt->wallet_id,
                    'category_id' => $rt->category_id,
                    'type' => $rt->type,
                    'amount' => $rt->amount,
                    'description' => $rt->description . ' (Otomatis)',
                    'transaction_date' => $txDate,
                    'to_wallet_id' => $rt->to_wallet_id,
                    'notes' => 'Generated automatically from recurring transaction #' . $rt->id,
                ]);

                // Calculate next date
                $nextDate = Carbon::parse($txDate);
                switch ($rt->frequency) {
                    case 'daily': $nextDate->addDay(); break;
                    case 'weekly': $nextDate->addWeek(); break;
                    case 'monthly': $nextDate->addMonth(); break;
                    case 'yearly': $nextDate->addYear(); break;
                }

                $rt->next_date = $nextDate->format('Y-m-d');
                $rt->save();
            });
            $count++;
        }

        $this->info("Processed {$count} recurring transactions.");
        Log::info("Processed {$count} recurring transactions.");
    }
}
