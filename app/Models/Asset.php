<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'user_id', 'name', 'symbol', 'type', 'quantity', 'average_buy_price', 'current_price', 'color',
    ];

    public function user() { return $this->belongsTo(User::class); }

    public function getTotalValueAttribute()
    {
        return $this->quantity * $this->current_price;
    }

    public function getTotalCostAttribute()
    {
        return $this->quantity * $this->average_buy_price;
    }

    public function getProfitLossAttribute()
    {
        return $this->total_value - $this->total_cost;
    }

    public function getRoiPercentageAttribute()
    {
        if ($this->total_cost > 0) {
            return ($this->profit_loss / $this->total_cost) * 100;
        }
        return 0;
    }
}
