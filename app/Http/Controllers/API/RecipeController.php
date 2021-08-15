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
    public function index()
    {
        $recipes = Recipe::paginate(25);
        foreach ($recipes as $key => $recipe) {
            $foodvalues = FoodValue::where('recipes_id', $recipe->id)->latest()->get();
            foreach ($foodvalues as $key1 => $foodvalue) {
                $fooditem = FoodItem::where('id', $foodvalue->food_items_id)->first();
                $categories = [];
                foreach ($fooditem->foodRelations as $key2 => $relation) {
                    array_push($categories, $relation->foodCategory);
                }
                $fooditem['categories'] = $categories;
                $fooditem['relations'] = $fooditem->foodRelations;
                $foodvalues[$key1]['food_item'] = $fooditem;
            }
            $category_name = Category::where('id', $recipe->categories_id)->get();
            $recipes[$key]['category_name'] = $category_name;
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
            'description' => 'required',
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
            ];
            return response()->json($response, 404);
        }

        $image = base64_encode($request->image);

        $recipe = Recipe::create([
            'title' => $request->title,
            'categories_id' => $request->categories_id,
            'description' => $request->description,
            'image' => $image,
        ]);

        $food_id = explode(",", $request->food_id);
        $food_amount = explode(",", $request->food_amount);

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
            'message' => 'Recipe created successfully.',
        ];

        return response()->json($response, 200);
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
        foreach ($foodvalues as $key => $foodvalue) {
            $foodvalues[$key]['food_item'] = FoodItem::where('id', $foodvalue->food_items_id)->first();
        }

        $response = [
            'success' => true,
            'message' => 'Recipe retrieved successfully.',
            'recipe' => $recipe,
            'foodvalues' => $foodvalues
        ];

        return response()->json($response, 200);
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

        return response()->json($response, 200);
    }
}
