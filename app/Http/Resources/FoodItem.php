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
            'portion_in_grams' => $this->portion_in_grams,
            'serving_size' => $this->serving_size,
            'serving_prefix' => $this->serving_prefix,
            'points' => $this->points,
            'units' => $this->units
        ];
    }
}
