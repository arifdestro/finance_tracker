<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'user_id', 'name', 'price', 'cycle', 'next_due_date', 'color', 'url',
    ];

    protected function casts(): array
    {
        return [
            'next_due_date' => 'date',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }

    public function getDaysUntilDueAttribute()
    {
        $today = Carbon::today();
        $dueDate = $this->next_due_date->startOfDay();
        return $today->diffInDays($dueDate, false);
    }
}
