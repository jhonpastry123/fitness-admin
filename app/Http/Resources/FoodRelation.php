<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\FoodCategory;

class FoodRelation extends JsonResource
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
            'food_item_id' => $this->food_item_id,
            'food_category_id' => $this->food_category_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'food_category' => FoodCategory::find($this->food_category_id),
        ];
    }
}
