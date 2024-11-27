<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    protected $primaryKey = 'leaderboard_id';

    protected $fillable = [
        'name', 
        'start_date', 
        'end_date', 
        'status'
    ];
}
