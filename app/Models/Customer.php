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
        'name', 'email', 'password', 'gender', 'birthday', 'active',  'type'
    ];
}
