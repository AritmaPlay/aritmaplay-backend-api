<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
{
    use HasFactory;

    protected $primaryKey = 'quiz_id';

    protected $fillable = [
        'quiz_mode', 
        'exp_received', 
        'total_question', 
        'quiz_time', 
        'correct_question', 
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
