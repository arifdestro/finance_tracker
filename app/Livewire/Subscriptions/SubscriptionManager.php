<?php

namespace App\Livewire\Subscriptions;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SubscriptionManager extends Component
{
    public $subscriptionId = null;
    public $name = '';
    public $price = '';
    public $cycle = 'monthly';
    public $next_due_date = '';
    public $color = 'indigo';
    public $url = '';

    public $isModalOpen = false;
    public $colors = ['indigo', 'rose', 'emerald', 'sky', 'amber', 'violet', 'fuchsia'];

    public function render()
    {
        $subscriptions = Subscription::where('user_id', Auth::id())
            ->orderBy('next_due_date', 'asc')
            ->get();

        // Calculate Burn Rates
        $monthlyBurnRate = 0;
        $yearlyBurnRate = 0;

        foreach ($subscriptions as $sub) {
            if ($sub->cycle === 'monthly') {
                $monthlyBurnRate += $sub->price;
                $yearlyBurnRate += ($sub->price * 12);
            } else {
                $monthlyBurnRate += ($sub->price / 12);
                $yearlyBurnRate += $sub->price;
            }
        }

        return view('livewire.subscriptions.subscription-manager', compact('subscriptions', 'monthlyBurnRate', 'yearlyBurnRate'))
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
        $this->subscriptionId = null;
        $this->name = '';
        $this->price = '';
        $this->cycle = 'monthly';
        $this->next_due_date = Carbon::now()->format('Y-m-d');
        $this->color = $this->colors[array_rand($this->colors)];
        $this->url = '';
    }

    public function edit($id)
    {
        $sub = Subscription::where('user_id', Auth::id())->findOrFail($id);
        $this->subscriptionId = $sub->id;
        $this->name = $sub->name;
        $this->price = round($sub->price);
        $this->cycle = $sub->cycle;
        $this->next_due_date = $sub->next_due_date->format('Y-m-d');
        $this->color = $sub->color;
        $this->url = $sub->url;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'cycle' => 'required|in:monthly,yearly',
            'next_due_date' => 'required|date',
            'color' => 'required|string',
            'url' => 'nullable|url',
        ]);

        if ($this->subscriptionId) {
            $sub = Subscription::where('user_id', Auth::id())->findOrFail($this->subscriptionId);
            $sub->update([
                'name' => $this->name,
                'price' => $this->price,
                'cycle' => $this->cycle,
                'next_due_date' => $this->next_due_date,
                'color' => $this->color,
                'url' => $this->url,
            ]);
            session()->flash('message', __('Langganan berhasil diperbarui.'));
        } else {
            Subscription::create([
                'user_id' => Auth::id(),
                'name' => $this->name,
                'price' => $this->price,
                'cycle' => $this->cycle,
                'next_due_date' => $this->next_due_date,
                'color' => $this->color,
                'url' => $this->url,
            ]);
            session()->flash('message', __('Langganan baru berhasil ditambahkan.'));
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        Subscription::where('user_id', Auth::id())->findOrFail($id)->delete();
        session()->flash('message', __('Langganan berhasil dihapus.'));
    }

    public function markAsPaid($id)
    {
        $sub = Subscription::where('user_id', Auth::id())->findOrFail($id);
        
        if ($sub->cycle === 'monthly') {
            $sub->next_due_date = $sub->next_due_date->addMonth();
        } else {
            $sub->next_due_date = $sub->next_due_date->addYear();
        }
        
        $sub->save();
        session()->flash('message', __('Langganan ditandai sudah dibayar. Jatuh tempo dimajukan ke ') . $sub->next_due_date->format('d M Y'));
    }
}
