<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    //
    protected $fillable = [
        'title', 'categories_id', 'description', 'image'
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'categories_id');
    }
}
