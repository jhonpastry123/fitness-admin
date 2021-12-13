<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Meal extends JsonResource
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
            'timing_id' => $this->timing_id,
            'serving_prefix' => $this->serving_prefix,
            'serving_size' => $this->serving_size,
            'serving_count' => $this->serving_count,
            'flag_carbon' => $this->iCarbon,
            'flag_protein' => $this->iProtein,
            'flag_fat' => $this->iFat,
            'carbon' => $this->carbon,
            'protein' => $this->protein,
            'fat' => $this->fat,
            'units' => $this->units,
            'points' => $this->points,
            'amount' => $this->amount,
            'pasta_num' => $this->pasta_num,
            'legumes_num' => $this->legumes_num,
            'oily_num' => $this->oily_num,
            'junk_img_num' => $this->junk_img_num,
            'fruit_num' => $this->fruit_num,
            'meat_num' => $this->meat_num,
            'oily_img_num' => $this->oily_img_num
        ];
    }
}
