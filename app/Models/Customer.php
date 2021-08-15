<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasApiTokens;
    //
    protected $fillable = [
        'name', 'email', 'password', 'type', 'goal', 'weight', 'initial_weight', 'gender', 'height', 'birthday', 'age', 'start_date', 'gymType', 'total_exercise', 'goal_weight', 'weekly_reduce', 'dietMode'
    ];
}
