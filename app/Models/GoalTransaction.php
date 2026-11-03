<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalTransaction extends Model
{
    protected $fillable = [
        'goal_id', 'wallet_id', 'amount', 'type', 'date', 'description',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function goal() { return $this->belongsTo(Goal::class); }
    public function wallet() { return $this->belongsTo(Wallet::class); }
}
