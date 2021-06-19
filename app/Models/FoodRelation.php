<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodRelation extends Model
{
    //
    public $fillable = ['food_item_id', 'food_category_id'];

    public function foodItem() {
        return $this->belongsTo(FoodItem::class, 'food_item_id', 'id');
    }

    public function foodCategory() {
        return $this->belongsTo(FoodCategory::class, 'food_category_id', 'id');
    }
}
