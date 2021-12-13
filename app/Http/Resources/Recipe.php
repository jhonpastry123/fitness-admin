<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Recipe extends JsonResource
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
            'title' => $this->title,
            'categories_id' => $this->categories_id,
            'description' => $this->description,
            'image' => $this->image,
            'units' => $this->units,
            'points' => $this->points,
            'amount' => $this->amount,
            'foodvalues' => FoodValue::collection($this->foodvalues),
        ];
    }
}
