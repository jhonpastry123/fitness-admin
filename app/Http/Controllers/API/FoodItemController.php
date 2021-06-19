<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FoodItem;
use App\Models\FoodValue;
use App\Models\FoodCategory;
use Validator;

class FoodItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //       
        $fooditems = FoodItem::all();
        foreach ($fooditems as $key => $fooditem) {
            $categories = [];
            foreach ($fooditem->foodRelations as $key2 => $relation) {
                array_push($categories, $relation->foodCategory);
            }
            $fooditem['categories'] = $categories;
        }

        $response = [
            'success' => true,
            'message' => 'FoodS retrieved successfully.',
            'fooditems' => $fooditems
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
        $fooditem['category'] = FoodCategory::where('id', $fooditem->food_categories_id)->first();
        // return response
        $response = [
            'success' => true,
            'message' => 'Book retrieved successfully.',
            'fooditem' => $fooditem
        ];
        return response()->json($response, 200);
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
