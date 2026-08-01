<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AssetManager extends Component
{
    public $assetId = null;
    public $name = '';
    public $symbol = '';
    public $type = 'crypto';
    public $quantity = '';
    public $average_buy_price = '';
    public $current_price = '';
    public $color = 'indigo';

    public $isModalOpen = false;
    public $isUpdatePriceModalOpen = false;
    public $updateAssetId = null;
    public $updateCurrentPrice = '';

    public $colors = ['indigo', 'rose', 'emerald', 'sky', 'amber', 'violet', 'fuchsia'];

    public function render()
    {
        $assets = Asset::where('user_id', Auth::id())->orderBy('name')->get();

        $totalNetWorth = 0;
        $totalCost = 0;

        foreach ($assets as $asset) {
            $totalNetWorth += $asset->total_value;
            $totalCost += $asset->total_cost;
        }

        $totalProfitLoss = $totalNetWorth - $totalCost;
        $totalRoiPercentage = $totalCost > 0 ? ($totalProfitLoss / $totalCost) * 100 : 0;

        return view('livewire.assets.asset-manager', compact('assets', 'totalNetWorth', 'totalCost', 'totalProfitLoss', 'totalRoiPercentage'))
            ->layout('layouts.app');
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetForm()
    {
        $this->assetId = null;
        $this->name = '';
        $this->symbol = '';
        $this->type = 'crypto';
        $this->quantity = '';
        $this->average_buy_price = '';
        $this->current_price = '';
        $this->color = $this->colors[array_rand($this->colors)];
    }

    public function edit($id)
    {
        $asset = Asset::where('user_id', Auth::id())->findOrFail($id);
        $this->assetId = $asset->id;
        $this->name = $asset->name;
        $this->symbol = $asset->symbol;
        $this->type = $asset->type;
        $this->quantity = (float) $asset->quantity;
        $this->average_buy_price = round($asset->average_buy_price);
        $this->current_price = round($asset->current_price);
        $this->color = $asset->color;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'nullable|string|max:20',
            'type' => 'required|in:crypto,stock,mutual_fund,gold,foreign_currency',
            'quantity' => 'required|numeric|min:0',
            'average_buy_price' => 'required|numeric|min:0',
            'current_price' => 'required|numeric|min:0',
            'color' => 'required|string',
        ]);

        if ($this->assetId) {
            $asset = Asset::where('user_id', Auth::id())->findOrFail($this->assetId);
            $asset->update([
                'name' => $this->name,
                'symbol' => strtoupper($this->symbol),
                'type' => $this->type,
                'quantity' => $this->quantity,
                'average_buy_price' => $this->average_buy_price,
                'current_price' => $this->current_price,
                'color' => $this->color,
            ]);
            session()->flash('message', __('Aset berhasil diperbarui.'));
        } else {
            Asset::create([
                'user_id' => Auth::id(),
                'name' => $this->name,
                'symbol' => strtoupper($this->symbol),
                'type' => $this->type,
                'quantity' => $this->quantity,
                'average_buy_price' => $this->average_buy_price,
                'current_price' => $this->current_price,
                'color' => $this->color,
            ]);
            session()->flash('message', __('Aset baru berhasil ditambahkan.'));
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        Asset::where('user_id', Auth::id())->findOrFail($id)->delete();
        session()->flash('message', __('Aset berhasil dihapus.'));
    }

    public function openUpdatePriceModal($id)
    {
        $asset = Asset::where('user_id', Auth::id())->findOrFail($id);
        $this->updateAssetId = $asset->id;
        $this->updateCurrentPrice = round($asset->current_price);
        $this->isUpdatePriceModalOpen = true;
    }

    public function closeUpdatePriceModal()
    {
        $this->isUpdatePriceModalOpen = false;
        $this->updateAssetId = null;
        $this->updateCurrentPrice = '';
    }

    public function updatePrice()
    {
        $this->validate([
            'updateCurrentPrice' => 'required|numeric|min:0',
        ]);

        $asset = Asset::where('user_id', Auth::id())->findOrFail($this->updateAssetId);
        $asset->update([
            'current_price' => $this->updateCurrentPrice
        ]);

        session()->flash('message', __('Harga pasar ' . $asset->name . ' berhasil diperbarui!'));
        $this->closeUpdatePriceModal();
    }
}
