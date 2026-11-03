<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'user_id', 'name', 'target_amount', 'current_amount', 'target_date', 'color', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'target_date' => 'date',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function transactions() { return $this->hasMany(GoalTransaction::class)->orderByDesc('date')->orderByDesc('created_at'); }

    public function getProgressPercentageAttribute()
    {
        if ($this->target_amount <= 0) return 0;
        $percentage = ($this->current_amount / $this->target_amount) * 100;
        return min(100, round($percentage, 1));
    }
}
