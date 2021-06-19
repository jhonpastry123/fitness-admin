<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodValue extends Model
{
    //
    protected $fillable = [
        'recipes_id', 'food_items_id', 'amount'
    ];

    public function foodItem() {
        return $this->belongsTo(FoodItem::class, 'food_items_id');
    }
}
