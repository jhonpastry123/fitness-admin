<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerInformation extends Model
{
    public $table = 'customer_informations';
    public $fillable = [
        'customer_id', 'goal', 'initial_weight', 'weight', 'gender', 'height', 'birthday', 'gym_type', 'sport_type1', 'sport_type2', 'sport_type3', 'sport_time1', 'sport_time2', 'sport_time3', 'goal_weight', 'weekly_goal', 'diet_mode', 'water', 'fruit', 'date'
    ];

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
