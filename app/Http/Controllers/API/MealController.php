<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\FoodItem;
use App\Models\FoodValue;
use App\Models\FoodCategory;
use Illuminate\Http\Request;

use App\Http\Resources\Meal as MealResource;

use Validator;
use Carbon\Carbon;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'customer_id' => 'required',
            'timing_id' => 'required',
            'food_id' => 'required',
            'recipe_id' => 'required',
            'gram' => 'required',
            'date' => 'required'
        ]);

        if ($validator->fails()) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
            ];
            return response()->json(false, 404);
        }

        $information = Meal::create($input);

        return response()->json(true, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function show(Meal $meal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function edit(Meal $meal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meal $meal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meal $meal)
    {
        $meal->delete();
        return response()->json(true, 200);
    }

    public function list(Request $request)
    {
        $user = $request->user();
        $date = $request->get('date');
        $timing_id = $request->get('timing_id');
        $query = Meal::query();
        $query->where('customer_id', $user->id)->where('date', $date);
        if ($timing_id) {
            $query->where('timing_id', $timing_id);
        }
        $meals = $query->latest()->get();
        foreach ($meals as $key => $meal) {
            $pasta_num = 0; 
            $legumes_num = 0;
            $oily_num = 0;
            $junk_img_num = 0;
            $fruit_num = 0;
            $meat_num = 0;
            $oily_img_num = 0;
            $iCarbon = 0;
            $iProtein = 0;
            $iFat = 0;

            if ($meal->food_id == 0) {
                $foodvalues = FoodValue::where('recipes_id', $meal->recipe->id)->latest()->get();
                $total_carbon = 0;
                $total_protein = 0;
                $total_fat = 0;
                $total_units = 0;
                $total_points = 0;
                $total_amount = 0;
                foreach ($foodvalues as $key1 => $foodvalue) {
                    $fooditem = FoodItem::where('id', $foodvalue->food_items_id)->first();

                    foreach ($fooditem->foodRelations as $key1 => $relation) {
                        switch($relation->food_category_id) {
                            case 1:
                                $pasta_num ++;
                                break;
                            case 2:
                                $legumes_num ++;
                                break;
                            case 3:
                                $oily_num ++;
                                break;
                            case 4:
                                $junk_img_num ++;
                                break;
                            case 10:
                                $fruit_num ++;
                                break;
                            case 7:
                            case 9:
                            case 11:
                            case 13:
                                $meat_num ++;
                                break;
                            case 52:
                                $iProtein = 1;
                                break;
                            case 53:
                                $iCarbon = 1;
                                break;
                            case 54:
                                $iFat = 1;
                                break;
                            case 59:
                                $oily_img_num ++;
                                break;
                            default:
                                break;
                        }
                    }

                    $points = ($fooditem->carbon / 15 + $fooditem->protein / 35 + $fooditem->fat / 15) * $foodvalue->amount / $fooditem->portion_in_grams;
                    if ((($points * 1000) % 100 ) > 75 ) {
                        $points = ceil($points*10) / 10;
                    } else {
                        $points = floor($points*10) / 10;
                    }

                    $units = ($fooditem->kcal * $foodvalue->amount) / (100 * $fooditem->portion_in_grams);
                    if ((($units * 1000) % 100 ) > 75 ) {
                        $units = ceil($units*10) / 10;
                    } else {
                        $units = floor($units*10) / 10;
                    }

                    $total_carbon += $fooditem->carbon * $foodvalue->amount / $fooditem->portion_in_grams;
                    $total_protein += $fooditem->protein * $foodvalue->amount / $fooditem->portion_in_grams;
                    $total_fat += $fooditem->fat * $foodvalue->amount / $fooditem->portion_in_grams;

                    $total_amount += $foodvalue->amount;
                    $total_points += $points;
                    $total_units += $units;
                }
                
                $meal['food_name'] = $meal->recipe->title;
                $meal['serving_prefix'] = "μερίδα";
                $meal['serving_count'] = 1;
                $meal['serving_size'] = $total_amount;
                $meal['units'] = $total_units;
                $meal['points'] = $total_points;
                $meal['amount'] = $total_amount;
                $meal['carbon'] = $total_carbon;
                $meal['protein'] = $total_protein;
                $meal['fat'] = $total_fat;
                
            } else if ($meal->recipe_id == 0) {

                $fooditem = $meal->foodItem;
                $amount = $meal->gram;
                $points = ($fooditem->carbon / 15 + $fooditem->protein / 35 + $fooditem->fat / 15)* $amount / $fooditem->portion_in_grams;
                if ((($points * 1000) % 100 ) > 75 ) {
                    $points = ceil($points*10) / 10;
                } else {
                    $points = floor($points*10) / 10;
                }

                $units = ($fooditem->kcal * $amount) / (100 * $fooditem->portion_in_grams);
                if ((($units * 1000) % 100 ) > 75 ) {
                    $units = ceil($units*10) / 10;
                } else {
                    $units = floor($units*10) / 10;
                }

                foreach ($fooditem->foodRelations as $key1 => $relation) {
                    switch($relation->food_category_id) {
                        case 1:
                            $pasta_num ++;
                            break;
                        case 2:
                            $legumes_num ++;
                            break;
                        case 3:
                            $oily_num ++;
                            break;
                        case 4:
                            $junk_img_num ++;
                            break;
                        case 10:
                            $fruit_num ++;
                            break;
                        case 7:
                        case 11:
                        case 13:
                            $meat_num ++;
                            break;
                        case 52:
                            $iProtein = 1;
                            break;
                        case 53:
                            $iCarbon = 1;
                            break;
                        case 54:
                            $iFat = 1;
                            break;
                        case 59:
                            $oily_img_num ++;
                            break;
                        default:
                            break;
                    }
                }

                
                $meal['carbon'] = $fooditem->carbon * $amount / $fooditem->portion_in_grams;
                $meal['protein'] = $fooditem->protein * $amount / $fooditem->portion_in_grams;
                $meal['fat'] = $fooditem->fat * $amount / $fooditem->portion_in_grams;

                $meal['food_name'] = $fooditem->food_name;
                $meal['serving_prefix'] = $fooditem->serving_prefix;
                $meal['serving_count'] = $amount / $fooditem->serving_size;
                $meal['serving_size'] = $fooditem->serving_size;
                $meal['units'] = $units;
                $meal['points'] = $points;
                $meal['amount'] = $amount;
            }

            $meal['pasta_num'] = $pasta_num;
            $meal['legumes_num'] = $legumes_num;
            $meal['oily_num'] = $oily_num;
            $meal['junk_img_num'] = $junk_img_num;
            $meal['fruit_num'] = $fruit_num;
            $meal['meat_num'] = $meat_num;
            $meal['oily_img_num'] = $oily_img_num;
            $meal['iCarbon'] = $iCarbon;
            $meal['iProtein'] = $iProtein;
            $meal['iFat'] = $iFat;
        }

        return MealResource::collection($meals);
    }
}
