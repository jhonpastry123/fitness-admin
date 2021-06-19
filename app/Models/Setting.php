<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'user_id', 'date', 'exercise_rate', 'sport1_type', 'sport1_time', 'sport2_type', 'sport2_time', 'sport3_type', 'sport3_time', 'height', 'weight', 'neck', 'waist', 'thigh', 'weekly_goal'
    ];
}
