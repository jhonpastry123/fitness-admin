<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerInformation extends JsonResource
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
            'customer_id' => $this->customer_id,
            'goal' => $this->goal,
            'initial_weight' => $this->initial_weight,
            'weight' => $this->weight,
            'gender' => $this->gender,
            'height' => $this->height,
            'birthday' => $this->birthday,
            'gym_type' => $this->gym_type,
            'sport_type1' => $this->sport_type1,
            'sport_type2' => $this->sport_type2,
            'sport_type3' => $this->sport_type3,
            'sport_time1' => $this->sport_time1,
            'sport_time2' => $this->sport_time2,
            'sport_time3' => $this->sport_time3,
            'goal_weight' => $this->goal_weight,
            'weekly_goal' => $this->weekly_goal,
            'diet_mode' => $this->diet_mode,
            'neck' => $this->neck,
            'waist' => $this->waist,
            'thigh' => $this->thigh,
            'water' => $this->water,
            'date' => $this->date,
        ];
    }
}
