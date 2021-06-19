<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    //
    protected $fillable = [
        'food_name', 'carbon', 'protein', 'fat', 'portion_in_grams', 'kcal', 'serving_size', 'serving_prefix'
    ];

    public function foodRelations() {
        return $this->hasMany(FoodRelation::class, 'food_item_id', 'id');
    }
}
