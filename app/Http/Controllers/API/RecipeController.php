<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\FoodValue;
use App\Models\Category;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use Validator;
use Storage;
use App\Http\Resources\Recipe as RecipeResource;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Recipe::query();
        $category = $request->get('category');
        $user_id = $request->get('user_id');
        $q = $request->get('q');
        if ($q) {
            $query->where('title', 'like', "%$q%");
        }
        $query->where('categories_id', $category)->where('user_id', $user_id);
        $recipes = $query->latest()->paginate();

        foreach ($recipes as $key => $recipe) {
            $foodvalues = FoodValue::where('recipes_id', $recipe->id)->latest()->get();
            $total_units = 0;
            $total_points = 0;
            $total_amount = 0;
            foreach ($foodvalues as $key1 => $foodvalue) {
                $fooditem = FoodItem::where('id', $foodvalue->food_items_id)->first();

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
                $fooditem['points'] = $points;
                $fooditem['units'] = $units;
                $foodvalues[$key1]['food_item'] = $fooditem;

                $total_amount += $foodvalue->amount;
                $total_points += $points;
                $total_units += $units;
            }
            $recipes[$key]['units'] = $total_units;
            $recipes[$key]['points'] = $total_points;
            $recipes[$key]['amount'] = $total_amount;
            $recipes[$key]['foodvalues'] = $foodvalues;
        }

        return RecipeResource::collection($recipes);
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
            'title' => 'required',
            'categories_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            // return response
            return response()->json(false, 200);
        }

        $recipe = Recipe::create([
            'title' => $request->title,
            'categories_id' => $request->categories_id,
            'description' => "",
            'image' => "",
            'user_id' => $request->user_id
        ]);

        $food_id = str_replace("[", "", $request->food_id);
        $food_id = str_replace("]", "", $food_id);
        $food_id = array_map('intval', explode(', ', $food_id));

        $food_amount = str_replace("[", "", $request->food_amount);
        $food_amount = str_replace("]", "", $food_amount);
        $food_amount = array_map('intval', explode(', ', $food_amount));

        for ($i=0; $i < count($food_id); $i++) {
            # code...
            FoodValue::create([
                'recipes_id' => $recipe->id,
                'food_items_id' => $food_id[$i],
                'amount' => str_replace(',', '.', $food_amount[$i])
            ]);
        }

        return response()->json(true, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function show(Recipe $recipe)
    {
        // return response
        $foodvalues = FoodValue::where('recipes_id', $recipe->id)->latest()->get();
        $total_units = 0;
        $total_points = 0;
        $total_amount = 0;
        foreach ($foodvalues as $key1 => $foodvalue) {
            $fooditem = FoodItem::where('id', $foodvalue->food_items_id)->first();

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

            $fooditem['points'] = $points;
            $fooditem['units'] = $units;
            $foodvalues[$key1]['food_item'] = $fooditem;

            $total_amount += $foodvalue->amount;
            $total_points += $points;
            $total_units += $units;
        }
        $recipe['units'] = $total_units;
        $recipe['points'] = $total_points;
        $recipe['amount'] = $total_amount;
        $recipe['foodvalues'] = $foodvalues;

        return RecipeResource::make($recipe);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipe $recipe)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'categories_id' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
            ];
            return response()->json($response, 404);
        }

        if (!$request->image)
            $image = $recipe->image;
        else
            $image = base64_encode($request->image);

        $recipe->update([
            'title' => $request->title,
            'categories_id' => $request->categories_id,
            'description' => $request->description,
            'image' => $image,
        ]);

        $food_id = explode(",", $request->food_id);
        $food_amount = explode(",", $request->food_amount);

        $foodvalues = FoodValue::where('recipes_id', $recipe->id)->get();
        for ($i = 0; $i < count($foodvalues); $i++) {
            # code...
            $foodvalues[$i]->delete();
        }

        for ($i = 0; $i < count($food_id); $i++) {
            # code...
            FoodValue::create([
                'recipes_id' => $recipe->id,
                'food_items_id' => $food_id[$i],
                'amount' => $food_amount[$i]
            ]);
        }

        // return response
        $response = [
            'success' => true,
            'message' => 'Recipe updated successfully.',
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe)
    {
        //
        $foodvalues = FoodValue::where('recipes_id', $recipe->id)->get();
        foreach ($foodvalues as $foodvalue) {
            $foodvalue->delete();
        }
        $recipe->delete();

        // return response
        $response = [
            'success' => true,
            'message' => 'Recipe deleted successfully.',
        ];

        return response()->json(true, 200);
    }

    public function list(Request $request)
    {
        $query = Recipe::query();
        $index = $request->get('index');
        $q = $request->get('q');
        if ($index) {
            $query->where('categories_id', $index);
        }
        if ($q) {
            $query->where('title', 'like', "%$q%");
        }
        $recipes = $query->latest()->get();
        foreach ($recipes as $key => $recipe) {
            $foodvalues = FoodValue::where('recipes_id', $recipe->id)->latest()->get();
            $total_units = 0;
            $total_points = 0;
            $total_amount = 0;
            foreach ($foodvalues as $key1 => $foodvalue) {
                $fooditem = FoodItem::where('id', $foodvalue->food_items_id)->first();

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

                $fooditem['points'] = $points;
                $fooditem['units'] = $units;
                $foodvalues[$key1]['food_item'] = $fooditem;

                $total_amount += $foodvalue->amount;
                $total_points += $points;
                $total_units += $units;
            }
            $recipes[$key]['units'] = $total_units;
            $recipes[$key]['points'] = $total_points;
            $recipes[$key]['amount'] = $total_amount;
            $recipes[$key]['foodvalues'] = $foodvalues;
        }

        return RecipeResource::collection($recipes);
    }
}
