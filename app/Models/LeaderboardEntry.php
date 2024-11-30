<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaderboardEntry extends Model
{
    protected $primaryKey = 'entry_id';

    protected $fillable = [
        'leaderboard_id', 
        'user_id', 
        'totalExpPerWeek',
        'last_updated'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function leaderboard()
    {
        return $this->belongsTo(Leaderboard::class, 'leaderboard_id', 'leaderboard_id');
    }
}
