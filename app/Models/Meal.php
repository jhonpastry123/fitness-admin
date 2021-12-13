<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    public $fillable = ['customer_id', 'timing_id', 'food_id', 'recipe_id', 'gram', 'date'];

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    
    public function foodItem() {
        return $this->belongsTo(FoodItem::class, 'food_id', 'id');
    }

    public function recipe() {
        return $this->belongsTo(Recipe::class, 'recipe_id', 'id');
    }
}
