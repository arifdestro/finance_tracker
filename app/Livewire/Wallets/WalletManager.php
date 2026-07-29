<?php

namespace App\Livewire\Wallets;

use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WalletManager extends Component
{
    public $wallets;
    public $name, $type = 'cash', $balance = 0, $icon, $color = '#10B981', $is_default = false;
    public $walletId = null;
    public $isModalOpen = false;

    public function render()
    {
        $this->wallets = Wallet::where('user_id', Auth::id())->orderByDesc('is_default')->get();
        return view('livewire.wallets.wallet-manager')->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->type = 'cash';
        $this->balance = 0;
        $this->icon = '';
        $this->color = '#10B981';
        $this->is_default = false;
        $this->walletId = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'type' => 'required|in:cash,bank,e-wallet',
            'balance' => 'required|numeric',
        ]);

        if ($this->is_default) {
            Wallet::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        Wallet::updateOrCreate(['id' => $this->walletId], [
            'name' => $this->name,
            'type' => $this->type,
            'balance' => $this->balance,
            'icon' => $this->icon,
            'color' => $this->color,
            'is_default' => $this->is_default,
            'user_id' => Auth::id(),
        ]);

        session()->flash('message', $this->walletId ? 'Dompet berhasil diupdate.' : 'Dompet berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $wallet = Wallet::where('user_id', Auth::id())->findOrFail($id);
        $this->walletId = $id;
        $this->name = $wallet->name;
        $this->type = $wallet->type;
        $this->balance = $wallet->balance;
        $this->icon = $wallet->icon;
        $this->color = $wallet->color;
        $this->is_default = $wallet->is_default;

        $this->openModal();
    }

    public function delete($id)
    {
        Wallet::where('user_id', Auth::id())->findOrFail($id)->delete();
        session()->flash('message', 'Dompet berhasil dihapus.');
    }
}
