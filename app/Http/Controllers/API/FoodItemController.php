<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FoodItem;
use App\Models\FoodValue;
use App\Models\FoodCategory;
use App\Http\Resources\FoodItem as FoodItemResource;
use Validator;

class FoodItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = FoodItem::query();
        $q = $request->get('q');
        if ($q) {
            $query->where('food_name', 'like', "%$q%");
        }
        $fooditems = $query->latest()->paginate();
        foreach ($fooditems as $key => $fooditem) {
            $points = $fooditem->carbon / 15 + $fooditem->protein / 35 + $fooditem->fat / 15;
            if ((($points * 1000) % 100 ) > 75 ) {
                $points = ceil($points*10) / 10;
            } else {
                $points = floor($points*10) / 10;
            }

            $units = $fooditem->kcal / 100;
            if ((($units * 1000) % 100 ) > 75 ) {
                $units = ceil($units*10) / 10;
            } else {
                $units = floor($units*10) / 10;
            }
            $fooditem['points'] = $points;
            $fooditem['units'] = $units;
        }

        return FoodItemResource::collection($fooditems);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'food_name' => 'required',
            'food_categories_id' => 'required',
            'carbon' => 'required',
            'protein' => 'required',
            'fat' => 'required',
            'portion_in_grams' => 'required',
            'kcal' => 'required',
        ]);

        if ($validator->fails()) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
            ];
            return response()->json($response, 404);
        }

        FoodItem::create($input);

        // return response
        $response = [
            'success' => true,
            'message' => 'FoodItem created successfully.',
        ];

        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FoodItem  $foodItem
     * @return \Illuminate\Http\Response
     */
    public function show(FoodItem $fooditem)
    {
        $points = $fooditem->carbon / 15 + $fooditem->protein / 35 + $fooditem->fat / 15;
        if ((($points * 1000) % 100 ) > 75 ) {
            $points = ceil($points*10) / 10;
        } else {
            $points = floor($points*10) / 10;
        }

        $units = $fooditem->kcal / 100;
        if ((($units * 1000) % 100 ) > 75 ) {
            $units = ceil($units*10) / 10;
        } else {
            $units = floor($units*10) / 10;
        }
        $fooditem['points'] = $points;
        $fooditem['units'] = $units;

        return FoodItemResource::make($fooditem);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FoodItem  $foodItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoodItem $fooditem)
    {
        //
        $input = $request->all();

        $validator = Validator::make($input, [
            'food_name' => 'required',
            'food_categories_id' => 'required',
            'carbon' => 'required',
            'protein' => 'required',
            'fat' => 'required',
            'portion_in_grams' => 'required',
            'kcal' => 'required'
        ]);

        if ($validator->fails()) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
            ];
            return response()->json($response, 404);
        }

        $fooditem->update($request->all());

        // return response
        $response = [
            'success' => true,
            'message' => 'FoodItem updated successfully.',
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FoodItem  $foodItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoodItem $fooditem)
    {
        //
        $foodvalues = FoodValue::where('food_items_id', $fooditem->id)->get();

        foreach ($foodvalues as $foodvalue) {
            $foodvalue->delete();
        }

        $fooditem->delete();

        // return response
        $response = [
            'success' => true,
            'message' => 'FoodItem deleted successfully.',
        ];

        return response()->json($response, 200);
    }
}
