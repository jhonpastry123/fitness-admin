<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    //
    public $table = 'test_test';
    protected $fillable = [
        'value', 'name'
    ];
}
