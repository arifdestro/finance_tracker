<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', \App\Livewire\Dashboard\Overview::class)->name('dashboard');
    
    // Categories
    Route::get('/categories', \App\Livewire\Categories\CategoryManager::class)->name('categories');
    
    // Budgets
    Route::get('/budgets', \App\Livewire\Budgets\BudgetManager::class)->name('budgets');
    
    // Reports
    Route::get('/reports', \App\Livewire\Reports\ReportManager::class)->name('reports');
    
    // Recurring Transactions
    Route::get('/recurring', \App\Livewire\RecurringTransactions\RecurringManager::class)->name('recurring');
    
    // Backup & Sync
    Route::get('/sync', \App\Livewire\DataSync\SyncManager::class)->name('sync');
    
    // Settings
    Route::get('/settings', \App\Livewire\Settings\SettingsManager::class)->name('settings');
    
    // Wallets
    Route::get('/wallets', \App\Livewire\Wallets\WalletManager::class)->name('wallets');
    
    // Transactions
    Route::get('/transactions', \App\Livewire\Transactions\TransactionList::class)->name('transactions.index');
    Route::get('/transactions/create', \App\Livewire\Transactions\TransactionForm::class)->name('transactions.create');
    Route::get('/transactions/{id}/edit', \App\Livewire\Transactions\TransactionForm::class)->name('transactions.edit');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
