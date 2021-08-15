<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FoodItem extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'food_name' => $this->food_name,
            'carbon' => $this->carbon,
            'protein' => $this->protein,
            'fat' => $this->fat,
            'portion_in_grams' => $this->portion_in_grams,
            'kcal' => $this->kcal,
            'serving_size' => $this->serving_size,
            'serving_prefix' => $this->serving_prefix,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'categories' => Category::collection($this->categories),
            'food_relations' => FoodRelation::collection($this->relations),
        ];
    }
}
